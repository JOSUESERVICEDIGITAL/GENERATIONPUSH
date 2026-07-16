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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'paid' => 'Payée',
            'pending' => 'En attente',
            'cancelled' => 'Annulée',
            'refunded' => 'Remboursée',
            default => $this->status,
        };
    }

    public static function nextOrderNumber(): string
    {
        $last = static::orderByDesc('id')->value('order_number');
        $number = $last ? ((int) substr($last, 4)) + 1 : 1;

        return 'ORD-' . str_pad((string) $number, 5, '0', STR_PAD_LEFT);
    }
}
