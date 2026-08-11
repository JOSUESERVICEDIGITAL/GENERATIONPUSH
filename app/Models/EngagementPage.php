<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EngagementPage extends Model
{
    protected $fillable = [
        'type', 'title', 'subtitle', 'description', 'benefits',
        'image_path', 'cta_label', 'is_visible',
    ];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }

    public static function forType(string $type): self
    {
        return static::firstOrCreate(['type' => $type], [
            'title' => $type === 'partner' ? 'Devenir partenaire' : 'Devenir bénévole',
            'cta_label' => $type === 'partner' ? 'Devenir partenaire' : 'Devenir bénévole',
        ]);
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }

    public function benefitsList(): array
    {
        return $this->benefits
            ? array_filter(array_map('trim', explode("\n", $this->benefits)))
            : [];
    }
}
