<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerApplication extends Model
{
    protected $fillable = [
        'organization',
        'sector',
        'contact_name',
        'position',
        'email',
        'phone',
        'country',
        'partnership_types',
        'partnership_other',
        'collaboration_project',
        'budget',
        'website',
        'discovery_source',
        'discovery_other',
        'document_path',
        'document_name',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'partnership_types' => 'array',
    ];
}

