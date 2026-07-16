<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservable_type',
        'reservable_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reservable(): MorphTo
    {
        return $this->morphTo();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'confirmed' => 'Confirmée',
            'pending' => 'En attente',
            'cancelled' => 'Annulée',
            default => $this->status,
        };
    }
}
