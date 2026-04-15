<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Admin only: list all reports
     */
    public function viewAny(?User $user): bool
    {
        return $user && $user->is_admin;
    }

    /**
     * Admin OR registered user who owns the report OR anonymous via session
     */
    public function view(?User $user, Report $report): bool
    {
        if ($user && $user->is_admin) {
            return true;
        }

        if ($user && $report->user_id === $user->id) {
            return true;
        }

        if (session('whistleblower_report_id') === $report->id) {
            return true;
        }

        return false;
    }

    /**
     * Admin only: update status
     */
    public function update(?User $user, Report $report): bool
    {
        return $user && $user->is_admin;
    }

    /**
     * Admin only: reveal identity
     */
    public function revealIdentity(?User $user, Report $report): bool
    {
        if (!$user || !$user->is_admin) {
            return false;
        }

        if ($report->is_anonymous || !$report->user_id) {
            return false;
        }

        if ($report->isIdentityRevealed()) {
            return false;
        }

        return true;
    }

    /**
     * Can send messages to the report
     */
    public function sendMessage(?User $user, Report $report): bool
    {
        if ($report->status === 'abgeschlossen') {
            return false;
        }

        return $this->view($user, $report);
    }

    /**
     * Can upload attachments to the report
     */
    public function uploadAttachment(?User $user, Report $report): bool
    {
        if ($user && $user->is_admin) {
            return true;
        }

        if (session('whistleblower_report_id') === $report->id) {
            return true;
        }

        return false;
    }
}
