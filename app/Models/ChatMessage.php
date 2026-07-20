<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'user_id',
        'author_id',
        'is_from_admin',
        'content',
        'edited_at',
        'read_by_admin_at',
        'read_by_member_at',
    ];

    protected function casts(): array
    {
        return [
            'is_from_admin' => 'boolean',
            'edited_at' => 'datetime',
            'read_by_admin_at' => 'datetime',
            'read_by_member_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
