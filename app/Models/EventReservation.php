<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventReservation extends Model
{
    use HasFactory;

   protected $fillable = [
    'event_id',
    'user_id',

    'first_name',
    'last_name',
    'email',
    'phone',

    'reference',
    'quantity',

    'status',
    'payment_status',

    'amount',
    'currency',

    'payment_method',
    'transaction_id',

    'notes',

    'confirmed_at',
    'cancelled_at',
];

    protected $casts = [
        'amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (EventReservation $reservation) {
            if (empty($reservation->reference)) {
                $reservation->reference =
                    'GP-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(Str::random(8));
            }
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }
}
