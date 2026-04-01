<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogService
{
    public static function log(
        int $reportId,
        string $action,
        ?string $oldValue = null,
        ?string $newValue = null,
        ?string $ipAddress = null
    ): void {
        ActivityLog::create([
            'report_id'  => $reportId,
            'user_id'    => auth()->id(),
            'action'     => $action,
            'old_value'  => $oldValue,
            'new_value'  => $newValue,
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }
}
