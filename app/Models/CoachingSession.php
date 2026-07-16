<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CoachingSession extends Model
{
    use HasFactory;

    protected $table = 'coaching_sessions';

    protected $fillable = [
        'title',
        'coach',
        'duration',
        'date',
        'capacity',
        'registered',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'capacity' => 'integer',
            'registered' => 'integer',
        ];
    }

    public function reservations(): MorphMany
    {
        return $this->morphMany(Reservation::class, 'reservable');
    }

    public function tickets(): MorphMany
    {
        return $this->morphMany(Ticket::class, 'ticketable');
    }

    public function attendancePercentage(): int
    {
        if ($this->capacity <= 0) {
            return 0;
        }

        return (int) round(($this->registered / $this->capacity) * 100);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'upcoming' => 'À venir',
            'ongoing' => 'En cours',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
            default => $this->status,
        };
    }
}
