<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    public static function log(
        int $reportId,
        string $action,
        ?string $oldValue = null,
        ?string $newValue = null,
        ?string $ipAddress = null
    ): void {
        // Anonymize IP for anonymous whistleblower actions (no authenticated user + session-based access)
        $isAnonymousWhistleblower = !auth()->check() && session()->has('whistleblower_report_id');

        ActivityLog::create([
            'report_id'  => $reportId,
            'user_id'    => auth()->id(),
            'action'     => $action,
            'old_value'  => $oldValue,
            'new_value'  => $newValue,
            'ip_address' => $isAnonymousWhistleblower ? null : ($ipAddress ?? request()->ip()),
        ]);
    }
}
