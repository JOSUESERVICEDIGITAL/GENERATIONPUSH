<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'price',
        'billing_cycle',
        'status',
        'started_at',
        'next_billing_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'started_at' => 'date',
            'next_billing_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function billingCycleLabel(): string
    {
        return $this->billing_cycle === 'yearly' ? 'Annuel' : 'Mensuel';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Actif',
            'cancelled' => 'Annulé',
            'expired' => 'Expiré',
            default => $this->status,
        };
    }
}
