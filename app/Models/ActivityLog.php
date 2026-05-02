<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'action',
        'old_value',
        'new_value',
        'ip_address',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Human-readable action labels
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'status_changed'    => __('messages.activity.status_changed'),
            'identity_revealed' => __('messages.activity.identity_revealed'),
            'report_accessed'   => __('messages.activity.report_accessed'),
            'message_sent'      => __('messages.activity.message_sent'),
            'report_created'    => __('messages.activity.report_created'),
            default             => $this->action,
        };
    }
}
