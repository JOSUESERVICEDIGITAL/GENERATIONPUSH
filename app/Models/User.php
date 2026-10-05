<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ChatMessageRead;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that should be mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'country',
        'city',
        'address',
        'profile_photo',
        'profile_completed_at',
        'status',
        'role',
        'chat_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'profile_completed_at' => 'datetime',
            'password' => 'hashed',
            'chat_enabled' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Messages du membre.
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(
            ChatMessage::class,
            'author_id'
        )->latest();
    }

    public function chatMessageReads(): HasMany
    {
        return $this->hasMany(
            ChatMessageRead::class,
            'user_id'
        );
    }

    /**
     * Articles enregistrés dans les favoris.
     */
    public function bookmarkedPosts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'post_bookmarks'
        )
            ->withTimestamps()
            ->latest('post_bookmarks.created_at');
    }

    /**
     * Commandes du membre.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Transactions / paiements du membre.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Réservations du membre.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie si le compte est actif.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Vérifie si le profil contient les informations nécessaires.
     */
    public function hasCompleteProfile(): bool
    {
        return filled($this->name)
            && filled($this->email)
            && filled($this->phone)
            && filled($this->country)
            && filled($this->city)
            && filled($this->address)
            && filled($this->profile_photo)
            && filled($this->profile_completed_at);
    }

    /**
     * Vérifie si le profil doit encore être complété.
     */
    public function needsProfileCompletion(): bool
    {
        return !$this->hasCompleteProfile();
    }

    /*
    |--------------------------------------------------------------------------
    | STATUT
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne le libellé du statut du compte.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'suspended' => 'Suspendu',
            default => $this->status,
        };
    }
}
