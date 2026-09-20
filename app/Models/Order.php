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

        // Snapshot client au moment de la commande
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_country',
        'customer_city',
        'customer_address',

        'contacted_at',
        'confirmed_at',
        'admin_notes',

        'notes',

        // Commande
        'total',
        'status',
        'delivery_status',

        // QR Code
        'qr_content',
        'qr_code_path',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'contacted_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUT DE LA COMMANDE
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

    /*
    |--------------------------------------------------------------------------
    | STATUT DE LIVRAISON
    |--------------------------------------------------------------------------
    */

    public function deliveryStatusLabel(): string
    {
        return match ($this->delivery_status) {
            'not_required' => 'Pas de livraison',
            'pending' => 'En attente',
            'processing' => 'Préparation',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            default => ucfirst((string) $this->delivery_status),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function requiresDelivery(): bool
    {
        return $this->items()
            ->where('fulfillment_type', 'physical')
            ->exists();
    }

    public function hasDigitalItems(): bool
    {
        return $this->items()
            ->where('fulfillment_type', 'digital')
            ->exists();
    }

    public function isCompleted(): bool
    {
        return $this->status === 'paid'
            && (
                ! $this->requiresDelivery()
                || $this->delivery_status === 'delivered'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | NUMÉRO DE COMMANDE
    |--------------------------------------------------------------------------
    */

    public static function nextOrderNumber(): string
    {
        $last = static::query()
            ->orderByDesc('id')
            ->value('order_number');

        $number = $last
            ? ((int) substr($last, 4)) + 1
            : 1;

        return 'ORD-' . str_pad(
            (string) $number,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}
