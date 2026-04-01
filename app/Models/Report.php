<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'violation_type',
        'incident_date',
        'incident_location',
        'involved_persons',
        'description',
        'status',
        'is_anonymous',
        'anonymous_username',
        'anonymous_password',
        'anonymous_token',
        'identity_revealed_at',
        'identity_revealed_by',
    ];

    protected $hidden = [
        'anonymous_password',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'identity_revealed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function revealedBy()
    {
        return $this->belongsTo(User::class, 'identity_revealed_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Check if identity has been revealed
     */
    public function isIdentityRevealed(): bool
    {
        return !is_null($this->identity_revealed_at);
    }

    /**
     * Get anonymized user info (for admin display)
     */
    public function getAnonymizedUserAttribute()
    {
        if ($this->is_anonymous) {
            return 'Anonym';
        }

        if ($this->isIdentityRevealed()) {
            return $this->user ? $this->user->name . ' (' . $this->user->email . ')' : 'Unbekannt';
        }

        return '[Identität geschützt]';
    }
}
