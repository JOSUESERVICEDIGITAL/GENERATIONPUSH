<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
    ];

    public function statusLabel(): string
    {
        return match ($this->status) {
            'new' => 'Nouveau',
            'read' => 'Lu',
            'replied' => 'Répondu',
            default => $this->status,
        };
    }
}
