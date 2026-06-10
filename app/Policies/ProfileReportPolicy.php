<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ProfileReport;
use App\Models\User;

final class ProfileReportPolicy
{
    /**
     * Determine if the user can create a report.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view/manage reports.
     */
    public function manage(User $user): bool
    {
        return $user->isAdmin();
    }
}
