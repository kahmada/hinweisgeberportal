<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $hidden = [
        'anonymous_password',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];
}
