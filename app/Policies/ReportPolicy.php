<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Admin only: list all reports
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Admin OR registered user who owns the report
     */
    public function view(User $user, Report $report): bool
    {
        return $user->is_admin || $report->user_id === $user->id;
    }

    /**
     * Admin only: update status
     */
    public function update(User $user, Report $report): bool
    {
        return $user->is_admin;
    }

    /**
     * Admin only: reveal identity
     */
    public function revealIdentity(User $user, Report $report): bool
    {
        return $user->is_admin;
    }
}
