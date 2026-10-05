<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Membre ayant passé la commande.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Articles de la commande.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUT
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /*
    |--------------------------------------------------------------------------
    | INFORMATIONS AFFICHABLES
    |--------------------------------------------------------------------------
    */

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'paid' => 'Payée',
            'cancelled' => 'Annulée',
            'refunded' => 'Remboursée',
            default => ucfirst((string) $this->status),
        };
    }
}
