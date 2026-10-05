<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_category_id',

        'title',
        'slug',
        'subtitle',
        'short_description',
        'description',

        'image',
        'banner',

        'speaker',
        'speaker_title',
        'speaker_image',

        'starts_at',
        'ends_at',

        'format',

        'venue',
        'address',
        'city',
        'country',

        'online_url',

        'reservation_enabled',
        'capacity',

        'reservation_starts_at',
        'reservation_ends_at',

        'is_free',
        'price',
        'currency',

        'status',
        'featured',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',

        'reservation_starts_at' => 'datetime',
        'reservation_ends_at' => 'datetime',

        'reservation_enabled' => 'boolean',
        'is_free' => 'boolean',
        'featured' => 'boolean',

        'price' => 'decimal:2',
        'capacity' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                $event->slug =
                    Str::slug($event->title) .
                    '-' .
                    Str::lower(Str::random(6));
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(
            EventCategory::class,
            'event_category_id'
        );
    }

    public function reservations()
    {
        return $this->hasMany(EventReservation::class);
    }

    public function confirmedReservations()
    {
        return $this->hasMany(EventReservation::class)
            ->where('status', 'confirmed');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query
            ->published()
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at');
    }

    public function scopePast($query)
    {
        return $query
            ->published()
            ->where('starts_at', '<', now())
            ->orderByDesc('starts_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /*
    |--------------------------------------------------------------------------
    | ÉTAT TEMPOREL
    |--------------------------------------------------------------------------
    */

    public function getTemporalStatusAttribute(): string
    {
        if ($this->starts_at->isFuture()) {
            return 'upcoming';
        }

        if (
            $this->ends_at &&
            now()->between($this->starts_at, $this->ends_at)
        ) {
            return 'ongoing';
        }

        return 'completed';
    }

    /*
    |--------------------------------------------------------------------------
    | RÉSERVATIONS
    |--------------------------------------------------------------------------
    */

    public function getReservedPlacesAttribute(): int
    {
        return (int) $this->reservations()
            ->where('status', 'confirmed')
            ->sum('quantity');
    }

    public function getRemainingPlacesAttribute(): ?int
    {
        if ($this->capacity === null) {
            return null;
        }

        return max(
            0,
            $this->capacity - $this->reserved_places
        );
    }

    public function getIsFullAttribute(): bool
    {
        if ($this->capacity === null) {
            return false;
        }

        return $this->remaining_places <= 0;
    }

    /*
    |--------------------------------------------------------------------------
    | DISPONIBILITÉ DES RÉSERVATIONS
    |--------------------------------------------------------------------------
    */

    public function getCanReserveAttribute(): bool
    {
        if (!$this->reservation_enabled) {
            return false;
        }

        if ($this->status !== 'published') {
            return false;
        }

        if ($this->starts_at->isPast()) {
            return false;
        }

        if ($this->is_full) {
            return false;
        }

        if (
            $this->reservation_starts_at &&
            now()->lt($this->reservation_starts_at)
        ) {
            return false;
        }

        if (
            $this->reservation_ends_at &&
            now()->gt($this->reservation_ends_at)
        ) {
            return false;
        }

        return true;
    }
}
