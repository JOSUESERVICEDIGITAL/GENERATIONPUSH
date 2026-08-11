<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EngagementApplication extends Model
{
    protected $fillable = [
        'type', 'name', 'email', 'phone', 'organization', 'message', 'status',
    ];

    public function typeLabel(): string
    {
        return $this->type === 'partner' ? 'Partenaire' : 'Bénévole';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'new' => 'Nouveau',
            'reviewed' => 'Examiné',
            'accepted' => 'Accepté',
            'rejected' => 'Refusé',
            default => $this->status,
        };
    }
}
