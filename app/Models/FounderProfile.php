<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class FounderProfile extends Model
{
    protected $fillable = [
        'name', 'role_title', 'main_photo_path',
        'bio', 'why_founded', 'mission',
        'facebook_url', 'instagram_url', 'twitter_url', 'linkedin_url', 'youtube_url',
        'show_bio', 'show_why_founded', 'show_mission', 'show_social', 'show_gallery',
        'is_page_enabled',
    ];

    protected function casts(): array
    {
        return [
            'show_bio' => 'boolean',
            'show_why_founded' => 'boolean',
            'show_mission' => 'boolean',
            'show_social' => 'boolean',
            'show_gallery' => 'boolean',
            'is_page_enabled' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return static::first() ?? static::create([]);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(FounderPhoto::class)->orderBy('order');
    }

    public function mainPhotoUrl(): ?string
    {
        return $this->main_photo_path ? Storage::disk('public')->url($this->main_photo_path) : null;
    }

    public function socials(): array
    {
        return array_filter([
            'facebook' => $this->facebook_url,
            'instagram' => $this->instagram_url,
            'twitter' => $this->twitter_url,
            'linkedin' => $this->linkedin_url,
            'youtube' => $this->youtube_url,
        ]);
    }
}
