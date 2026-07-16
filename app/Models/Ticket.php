<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticketable_type',
        'ticketable_id',
        'title',
        'price',
        'quantity_total',
        'quantity_sold',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity_total' => 'integer',
            'quantity_sold' => 'integer',
        ];
    }

    public function ticketable(): MorphTo
    {
        return $this->morphTo();
    }

    public function remaining(): int
    {
        return max(0, $this->quantity_total - $this->quantity_sold);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Actif',
            'sold_out' => 'Épuisé',
            'closed' => 'Clôturé',
            default => $this->status,
        };
    }
}
