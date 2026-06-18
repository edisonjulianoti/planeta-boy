<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use App\Observers\ProfileObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProfileVerifiedAutoTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Profile $profile;
    private ProfileObserver $observer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email_verified_at' => now(),
            'plan'              => 'premium',
            'plan_expires_at'   => now()->addMonth(),
        ]);

        $this->profile = Profile::factory()->create([
            'user_id'             => $this->user->id,
            'verified'            => false,
            'verified_manually'   => false,
            'verification_status' => 'approved',
            'documents_verified'  => true,
            'no_reports'          => true,
            'clean_history'       => true,
        ]);

        $this->observer = new ProfileObserver();
    }

    // ─── shouldBeVerified() method tests ───────────────────

    public function test_should_be_verified_when_all_requirements_met(): void
    {
        $result = $this->observer->shouldBeVerified($this->profile->fresh());
        $this->assertTrue($result);
    }

    public function test_should_not_be_verified_when_email_not_verified(): void
    {
        // Must use save() instead of update() to properly set datetime cast to null
        $this->user->email_verified_at = null;
        $this->user->save();

        $freshUser = User::find($this->user->id);
        $this->assertNull($freshUser->email_verified_at);

        $result = $this->observer->shouldBeVerified($this->profile->fresh());
        $this->assertFalse($result);
    }

    public function test_should_not_be_verified_when_documents_not_verified(): void
    {
        $this->profile->documents_verified = false;
        $result = $this->observer->shouldBeVerified($this->profile);
        $this->assertFalse($result);
    }

    public function test_should_not_be_verified_when_no_reports_is_false(): void
    {
        $this->profile->no_reports = false;
        $result = $this->observer->shouldBeVerified($this->profile);
        $this->assertFalse($result);
    }

    public function test_should_not_be_verified_when_clean_history_is_false(): void
    {
        $this->profile->clean_history = false;
        $result = $this->observer->shouldBeVerified($this->profile);
        $this->assertFalse($result);
    }

    public function test_should_not_be_verified_when_plan_expired(): void
    {
        $this->user->plan_expires_at = now()->subDay();
        $this->user->save();

        $result = $this->observer->shouldBeVerified($this->profile->fresh());
        $this->assertFalse($result);
    }

    public function test_should_not_be_verified_when_user_not_found(): void
    {
        $profile = $this->profile;
        $profile->user_id = 99999;
        $result = $this->observer->shouldBeVerified($profile);
        $this->assertFalse($result);
    }

    // ─── Observer events ──────────────────────────────────────

    public function test_observer_overrides_verified_on_save_when_dirty(): void
    {
        // documents_verified changes make model dirty → saving fires
        $this->profile->documents_verified = false;
        $this->profile->save();

        $this->profile->refresh();
        $this->assertFalse($this->profile->verified);
    }

    public function test_observer_does_not_override_manually_verified(): void
    {
        $this->profile->verified = true;
        $this->profile->verified_manually = true;
        $this->profile->documents_verified = false;
        $this->profile->no_reports = false;
        $this->profile->clean_history = false;
        $this->profile->save();

        $this->profile->refresh();
        $this->assertTrue($this->profile->verified);
        $this->assertTrue($this->profile->verified_manually);
    }

    public function test_observer_does_not_override_manually_unverified(): void
    {
        $this->profile->verified_manually = true;
        $this->profile->verified = false;
        $this->profile->save();

        $this->profile->refresh();
        $this->assertFalse($this->profile->verified);
    }

    public function test_observer_can_auto_verify_after_save_when_dirty(): void
    {
        // Start with profile that fails criteria
        $this->profile->documents_verified = false;
        $this->profile->save();

        $this->profile->refresh();
        $this->assertFalse($this->profile->verified);

        // Now fix criteria → auto verify
        $this->profile->documents_verified = true;
        $this->profile->save();

        $this->profile->refresh();
        $this->assertTrue($this->profile->verified);
        $this->assertFalse($this->profile->verified_manually);
    }

    // ─── RecalculateAll ─────────────────────────────────────

    public function test_recalculate_all_uses_fresh_user_data(): void
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        ProfileObserver::recalculateAll();

        $this->profile->refresh();
        $this->assertFalse($this->profile->verified);
    }

    public function test_recalculate_all_skips_manually_verified(): void
    {
        $this->profile->verified = true;
        $this->profile->verified_manually = true;
        $this->profile->no_reports = false;
        $this->profile->save();

        ProfileObserver::recalculateAll();

        $this->profile->refresh();
        $this->assertTrue($this->profile->verified); // preserved manually
    }

    public function test_recalculate_all_removes_verified_when_missing_requirements(): void
    {
        $this->profile->no_reports = false;
        $this->profile->save();

        ProfileObserver::recalculateAll();

        $this->profile->refresh();
        $this->assertFalse($this->profile->verified);
    }

    // ─── Helper methods ─────────────────────────────────────

    public function test_missing_verified_requirements_returns_expected_array(): void
    {
        $this->profile->documents_verified = false;
        $this->profile->no_reports = false;
        $this->profile->clean_history = false;

        $missing = $this->profile->missingVerifiedRequirements();

        $this->assertContains('Documentos não verificados', $missing);
        $this->assertContains('Possui denúncias não resolvidas', $missing);
        $this->assertContains('Histórico recente com violações', $missing);
    }

    public function test_is_verified_by_admin_distinguishes_manual_from_auto(): void
    {
        $this->profile->verified = true;

        $this->profile->verified_manually = true;
        $this->assertTrue($this->profile->isVerifiedByAdmin());
        $this->assertFalse($this->profile->isAutoVerified());

        $this->profile->verified_manually = false;
        $this->assertFalse($this->profile->isVerifiedByAdmin());
        $this->assertTrue($this->profile->isAutoVerified());
    }

    // ─── Admin actions ─────────────────────────────────────

    public function test_admin_update_sets_verified_manually(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->put(route('admin.profiles.update', $this->profile), [
                'name'   => $this->profile->name,
                'age'    => $this->profile->age,
                'city'   => $this->profile->city,
                'state'  => $this->profile->state,
                'verified' => '1',
                'active'   => '1',
            ])
            ->assertRedirect();

        $this->profile->refresh();
        $this->assertTrue($this->profile->verified);
        $this->assertTrue($this->profile->verified_manually);
    }

    public function test_admin_uncheck_verified_releases_auto_mode(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->put(route('admin.profiles.update', $this->profile), [
                'name'     => $this->profile->name,
                'age'      => $this->profile->age,
                'city'     => $this->profile->city,
                'state'    => $this->profile->state,
                'verified' => '0',
                'active'   => '1',
            ])
            ->assertRedirect();

        $this->profile->refresh();
        // verified_manually = false → observer recalculates → true (criteria met)
        $this->assertTrue($this->profile->verified);
        $this->assertFalse($this->profile->verified_manually);
    }
}
