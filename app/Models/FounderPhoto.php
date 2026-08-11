<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FounderPhoto extends Model
{
    protected $fillable = ['founder_profile_id', 'image_path', 'order'];

    protected function casts(): array
    {
        return ['order' => 'integer'];
    }

    public function founderProfile(): BelongsTo
    {
        return $this->belongsTo(FounderProfile::class);
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }
}
