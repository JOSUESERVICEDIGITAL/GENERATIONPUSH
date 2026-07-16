<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'method',
        'status',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function methodLabel(): string
    {
        return match ($this->method) {
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            'orange' => 'Orange Money',
            'wave' => 'Wave',
            default => $this->method,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'completed' => 'Confirmé',
            'pending' => 'En attente',
            'failed' => 'Échoué',
            default => $this->status,
        };
    }
}
