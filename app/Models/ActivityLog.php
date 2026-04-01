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
            'status_changed'    => 'Status geändert',
            'identity_revealed' => 'Identität enthüllt',
            'report_accessed'   => 'Hinweis aufgerufen',
            'message_sent'      => 'Nachricht gesendet',
            default             => $this->action,
        };
    }
}
