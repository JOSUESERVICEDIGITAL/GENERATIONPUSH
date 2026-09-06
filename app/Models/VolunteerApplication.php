<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerApplication extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'city_country',
        'age',
        'profession',
        'contribution_areas',
        'contribution_other',
        'motivation',
        'skills',
        'availability',
        'participated_before',
        'social_link',
        'document_path',
        'document_name',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'age' => 'integer',
        'participated_before' => 'boolean',
        'contribution_areas' => 'array',
    ];
}
