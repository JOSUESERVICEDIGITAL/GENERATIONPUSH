<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'is_from_admin',
        'content',
        'edited_at',
    ];

    protected function casts(): array
    {
        return [
            'is_from_admin' => 'boolean',
            'edited_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Auteur du message
    |--------------------------------------------------------------------------
    */

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Lectures du message
    |--------------------------------------------------------------------------
    */

    public function reads(): HasMany
    {
        return $this->hasMany(
            ChatMessageRead::class,
            'chat_message_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Vérifier si un utilisateur a lu le message
    |--------------------------------------------------------------------------
    */

    public function isReadBy(User $user): bool
    {
        return $this->reads()
            ->where('user_id', $user->id)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Marquer le message comme lu
    |--------------------------------------------------------------------------
    */

    public function markAsReadBy(User $user): void
    {
        ChatMessageRead::firstOrCreate(
            [
                'chat_message_id' => $this->id,
                'user_id' => $user->id,
            ],
            [
                'read_at' => now(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Nombre de lecteurs
    |--------------------------------------------------------------------------
    */

    public function readersCount(): int
    {
        return $this->reads()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Nombre de membres n'ayant pas encore lu
    |--------------------------------------------------------------------------
    */

    public function unreadCount(): int
    {
        return User::query()
            ->where('chat_enabled', true)
            ->whereDoesntHave('chatMessageReads', function ($query) {
                $query->where('chat_message_id', $this->id);
            })
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Libellé de l'auteur
    |--------------------------------------------------------------------------
    */

    public function authorLabel(): string
    {
        return $this->is_from_admin
            ? 'Équipe Generation PUSH'
            : ($this->author?->name ?? 'Membre');
    }

    /*
    |--------------------------------------------------------------------------
    | Message envoyé par l'administration
    |--------------------------------------------------------------------------
    */

    public function isFromAdmin(): bool
    {
        return $this->is_from_admin === true;
    }

    /*
    |--------------------------------------------------------------------------
    | Message envoyé par un membre
    |--------------------------------------------------------------------------
    */

    public function isFromMember(): bool
    {
        return $this->is_from_admin === false;
    }
}
