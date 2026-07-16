<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
        'website_url',
        'tier',
        'status',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function logoUrl(): ?string
    {
        return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null;
    }

    public function tierLabel(): string
    {
        return match ($this->tier) {
            'platinum' => 'Platine',
            'gold' => 'Or',
            'silver' => 'Argent',
            'bronze' => 'Bronze',
            default => $this->tier,
        };
    }

    public function statusLabel(): string
    {
        return $this->status === 'active' ? 'Actif' : 'Inactif';
    }
}
