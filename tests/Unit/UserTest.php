<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_admin_returns_correct_value(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_is_blocked_returns_correct_value(): void
    {
        $blockedUser = User::factory()->blocked()->create();
        $user = User::factory()->create();

        $this->assertTrue($blockedUser->isBlocked());
        $this->assertFalse($user->isBlocked());
    }

    public function test_has_plan_returns_true_for_admins(): void
    {
        $admin = User::factory()->admin()->create(['plan' => 'free']);

        $this->assertTrue($admin->hasPlan('premium'));
        $this->assertTrue($admin->hasPlan('free'));
    }

    public function test_has_plan_returns_true_for_matching_plan(): void
    {
        $user = User::factory()->create(['plan' => 'premium']);

        $this->assertTrue($user->hasPlan('premium'));
        $this->assertFalse($user->hasPlan('free'));
    }

    public function test_has_plan_returns_false_for_non_matching_plan(): void
    {
        $user = User::factory()->create(['plan' => 'free']);

        $this->assertTrue($user->hasPlan('free'));
        $this->assertFalse($user->hasPlan('premium'));
    }

    public function test_plan_is_active_returns_true_for_admins(): void
    {
        $admin = User::factory()->admin()->create(['plan' => 'free', 'plan_expires_at' => null]);

        $this->assertTrue($admin->planIsActive());
    }

    public function test_plan_is_active_returns_true_for_free_plan(): void
    {
        $user = User::factory()->create(['plan' => 'free', 'plan_expires_at' => null]);

        $this->assertTrue($user->planIsActive());
    }

    public function test_plan_is_active_returns_true_for_valid_premium_plan(): void
    {
        $user = User::factory()->premium()->create();

        $this->assertTrue($user->planIsActive());
    }

    public function test_plan_is_active_returns_false_for_expired_premium_plan(): void
    {
        $user = User::factory()->premiumExpired()->create();

        $this->assertFalse($user->planIsActive());
    }

    public function test_has_pending_subscription_request(): void
    {
        // Este teste requer verificar se há solicitações pendentes
        // A implementação depende do model SubscriptionRequest
        $user = User::factory()->create();

        $this->assertFalse($user->hasPendingSubscriptionRequest());
    }

    public function test_should_have_plan_returns_false_for_admins(): void
    {
        $admin = User::factory()->admin()->create();

        $this->assertFalse($admin->shouldHavePlan());
    }

    public function test_should_have_plan_returns_true_for_regular_users(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($user->shouldHavePlan());
    }

    public function test_subscription_requests_relationship(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->subscriptionRequests());
    }
}
