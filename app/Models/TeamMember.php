<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'photo_path',
        'bio',
        'email',
        'linkedin_url',
        'order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function photoUrl(): ?string
    {
        return $this->photo_path ? Storage::disk('public')->url($this->photo_path) : null;
    }

    public function statusLabel(): string
    {
        return $this->status === 'active' ? 'Actif' : 'Inactif';
    }
}
