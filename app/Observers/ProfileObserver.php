<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;

class ProfileObserver
{
    /**
     * Auto-calculate the 'verified' flag on save.
     *
     * Only overrides if verified_manually is false.
     * When true, admin has explicitly set the value — respect that.
     */
    public function saving(Profile $profile): void
    {
        if ($profile->verified_manually) {
            return;
        }

        $profile->verified = $this->shouldBeVerified($profile);
    }

    /**
     * Determine if a profile should have the verified badge based on all criteria.
     * Uses fresh DB queries for user data to avoid stale relationship cache.
     */
    public function shouldBeVerified(Profile $profile): bool
    {
        $user = User::find($profile->user_id);
        if (!$user) {
            return false;
        }

        // 1. Email verified
        $emailOk = $user->hasVerifiedEmail();

        // 2. Documents verified (KYC aprovado)
        $docsOk = (bool) $profile->documents_verified;

        // 3. No unresolved reports
        $reportsOk = (bool) $profile->no_reports;

        // 4. Clean history (no violations in last 90 days)
        $historyOk = (bool) $profile->clean_history;

        // 5. Active plan (not free or has active subscription)
        $planOk = $user->planIsActive();

        return $emailOk && $docsOk && $reportsOk && $historyOk && $planOk;
    }

    /**
     * After documents are approved, auto-update no_reports / clean_history
     * and recalculate verified.
     */
    public function updated(Profile $profile): void
    {
        // When documents_verified changes, re-check reports status
        if ($profile->wasChanged('documents_verified') && $profile->documents_verified) {
            $this->updateReportFlags($profile);
        }
    }

    /**
     * Update no_reports and clean_history based on actual report data.
     */
    public function updateReportFlags(Profile $profile): void
    {
        $hasUnresolvedReports = $profile->reports()
            ->whereNull('resolved_at')
            ->exists();

        $profile->no_reports = !$hasUnresolvedReports;

        $hasRecentViolation = $profile->reports()
            ->where('resolved_at', '>=', Carbon::now()->subDays(90))
            ->exists();

        $profile->clean_history = !$hasRecentViolation;

        if (!$profile->verified_manually) {
            $profile->verified = $this->shouldBeVerified($profile);
        }

        $profile->saveQuietly();
    }

    /**
     * Recalculate verified for all non-manual profiles.
     * Called by the daily cron job.
     */
    public static function recalculateAll(): int
    {
        $count = 0;
        Profile::where('verified_manually', false)
            ->chunk(100, function ($profiles) use (&$count) {
                foreach ($profiles as $profile) {
                    // Touch timestamp to ensure save() fires saving event
                    $profile->updated_at = now();
                    $profile->save();
                    $count++;
                }
            });
        return $count;
    }
}
