<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\SubscriptionRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    private function seedPlans(): void
    {
        Plan::create(['name' => 'Free',    'slug' => 'free',    'price' => 0,   'active' => true, 'features' => []]);
        Plan::create(['name' => 'Premium', 'slug' => 'premium', 'price' => 199, 'active' => true, 'features' => []]);
    }

    public function test_plans_page_is_accessible_to_guests(): void
    {
        $this->seedPlans();

        $this->get(route('planos'))->assertOk()->assertViewIs('planos');
    }

    public function test_guest_cannot_contract_plan(): void
    {
        $this->seedPlans();

        $this->post(route('planos.contratar'), ['plan_slug' => 'premium'])
            ->assertRedirect(route('login'));
    }

    public function test_user_can_request_plan_upgrade(): void
    {
        $this->seedPlans();

        $user = User::factory()->create(['plan' => 'free']);

        $this->actingAs($user)
            ->post(route('planos.contratar'), ['plan_slug' => 'premium'])
            ->assertRedirect(route('meu.plano'));

        $this->assertDatabaseHas('subscription_requests', [
            'user_id'   => $user->id,
            'plan_slug' => 'premium',
            'status'    => 'pending',
        ]);
    }

    public function test_user_cannot_request_duplicate_pending(): void
    {
        $this->seedPlans();

        $user = User::factory()->create(['plan' => 'free']);

        SubscriptionRequest::create([
            'user_id'   => $user->id,
            'plan_slug' => 'premium',
            'status'    => 'pending',
        ]);

        $this->actingAs($user)
            ->post(route('planos.contratar'), ['plan_slug' => 'premium'])
            ->assertRedirect();

        $this->assertSame(1, SubscriptionRequest::where('user_id', $user->id)->count());
    }

    public function test_user_cannot_request_current_plan(): void
    {
        $this->seedPlans();

        $user = User::factory()->create(['plan' => 'premium']);

        $this->actingAs($user)
            ->post(route('planos.contratar'), ['plan_slug' => 'premium'])
            ->assertRedirect();

        $this->assertDatabaseMissing('subscription_requests', ['user_id' => $user->id]);
    }

    public function test_admin_can_approve_subscription_request(): void
    {
        $this->seedPlans();

        $admin = User::factory()->admin()->create();
        $user  = User::factory()->create(['plan' => 'free']);

        $req = SubscriptionRequest::create([
            'user_id'   => $user->id,
            'plan_slug' => 'premium',
            'status'    => 'pending',
        ]);

        $expiresAt = now()->addMonth()->format('Y-m-d');

        $this->actingAs($admin)
            ->post(route('admin.subscriptions.approve', $req), [
                'expires_at'  => $expiresAt,
                'admin_notes' => 'Aprovado manualmente',
            ])
            ->assertRedirect();

        $req->refresh();
        $this->assertSame(SubscriptionStatus::Approved, $req->status);
        $this->assertSame('Aprovado manualmente', $req->admin_notes);

        $user->refresh();
        $this->assertSame('premium', $user->plan);
        $this->assertNotNull($user->plan_expires_at);
    }

    public function test_admin_can_reject_subscription_request(): void
    {
        $this->seedPlans();

        $admin = User::factory()->admin()->create();
        $user  = User::factory()->create(['plan' => 'free']);

        $req = SubscriptionRequest::create([
            'user_id'   => $user->id,
            'plan_slug' => 'premium',
            'status'    => 'pending',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.subscriptions.reject', $req), [
                'admin_notes' => 'Documentação insuficiente',
            ])
            ->assertRedirect();

        $req->refresh();
        $this->assertSame(SubscriptionStatus::Rejected, $req->status);
        $this->assertSame('free', $user->fresh()->plan);
    }

    public function test_admin_can_update_user_plan_directly(): void
    {
        $this->seedPlans();

        $admin = User::factory()->admin()->create();
        $user  = User::factory()->create(['plan' => 'free']);

        $expiresAt = now()->addMonths(3)->format('Y-m-d');

        $this->actingAs($admin)
            ->post(route('admin.users.plan.update', $user), [
                'plan'            => 'premium',
                'plan_expires_at' => $expiresAt,
            ])
            ->assertRedirect();

        $user->refresh();
        $this->assertSame('premium', $user->plan);
        $this->assertNotNull($user->plan_expires_at);
    }

    public function test_user_can_cancel_plan(): void
    {
        $this->seedPlans();

        $user = User::factory()->create([
            'plan'            => 'premium',
            'plan_expires_at' => now()->addMonth(),
        ]);

        $this->actingAs($user)
            ->post(route('meu.plano.cancelar'))
            ->assertRedirect();

        $user->refresh();
        $this->assertSame('free', $user->plan);
        $this->assertNull($user->plan_expires_at);
    }

    public function test_user_on_free_plan_cannot_cancel(): void
    {
        $this->seedPlans();

        $user = User::factory()->create(['plan' => 'free']);

        $this->actingAs($user)
            ->post(route('meu.plano.cancelar'))
            ->assertRedirect();

        $this->assertSame('free', $user->fresh()->plan);
    }

    public function test_plan_is_active_when_not_expired(): void
    {
        $user = User::factory()->create([
            'plan'            => 'premium',
            'plan_expires_at' => now()->addDays(10),
        ]);

        $this->assertTrue($user->planIsActive());
    }

    public function test_plan_is_inactive_when_expired(): void
    {
        $user = User::factory()->create([
            'plan'            => 'premium',
            'plan_expires_at' => now()->subDay(),
        ]);

        $this->assertFalse($user->planIsActive());
    }

    public function test_free_plan_is_always_active(): void
    {
        $user = User::factory()->create(['plan' => 'free']);

        $this->assertTrue($user->planIsActive());
    }
}
