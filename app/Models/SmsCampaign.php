<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'audience',
        'recipients_count',
        'status',
        'scheduled_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'recipients_count' => 'integer',
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function audienceLabel(): string
    {
        return match ($this->audience) {
            'members' => 'Membres',
            'leaders' => 'Leaders',
            default => 'Tous les utilisateurs',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'sent' => 'Envoyée',
            'scheduled' => 'Programmée',
            default => 'Brouillon',
        };
    }
}
