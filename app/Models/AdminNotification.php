<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'audience',
        'recipients_count',
        'status',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'recipients_count' => 'integer',
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
        return $this->status === 'sent' ? 'Envoyée' : 'Brouillon';
    }
}
