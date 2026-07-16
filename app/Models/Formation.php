<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'trainer',
        'duration',
        'participants',
        'price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'participants' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'En cours',
            'draft' => 'Brouillon',
            'completed' => 'Terminé',
            default => $this->status,
        };
    }
}
