<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{
    protected $fillable = [

        'key',
        'title',
        'subtitle',
        'status',

        /*
        |--------------------------------------------------------------------------
        | Banner
        |--------------------------------------------------------------------------
        */

        'banner_video',
        'banner_poster',
        'banner_video_enabled',

        /*
        |--------------------------------------------------------------------------
        | Histoire
        |--------------------------------------------------------------------------
        */

        'story_eyebrow',
        'story_title',
        'story_text',
        'story_image',
        'story_card_title',
        'story_card_text',

        /*
        |--------------------------------------------------------------------------
        | Fondatrice
        |--------------------------------------------------------------------------
        */

        'founder_eyebrow',
        'founder_title',
        'founder_text',
        'founder_button_text',

        /*
        |--------------------------------------------------------------------------
        | Valeurs
        |--------------------------------------------------------------------------
        */

        'values_eyebrow',
        'values_title',
        'values_intro',

        'value_1_title',
        'value_1_short',
        'value_1_text',

        'value_2_title',
        'value_2_short',
        'value_2_text',

        'value_3_title',
        'value_3_short',
        'value_3_text',

        /*
        |--------------------------------------------------------------------------
        | Équipe
        |--------------------------------------------------------------------------
        */

        'team_eyebrow',
        'team_title',
        'team_intro',

        /*
        |--------------------------------------------------------------------------
        | CTA
        |--------------------------------------------------------------------------
        */

        'cta_eyebrow',
        'cta_title',
        'cta_highlight',
        'cta_text',
        'cta_button_text',
        'cta_button_url',


        'shop_hero_eyebrow',
        'shop_hero_title',
        'shop_hero_highlight',
        'shop_hero_text',

        'shop_catalogue_eyebrow',
        'shop_catalogue_title',
        'shop_catalogue_highlight',
        'shop_catalogue_text',

        'shop_cta_eyebrow',
        'shop_cta_title',
        'shop_cta_highlight',
        'shop_cta_text',
    ];


    protected function casts(): array
    {
        return [
            'banner_video_enabled' => 'boolean',
        ];
    }


    public function getRouteKeyName(): string
    {
        return 'key';
    }


    /*
    |--------------------------------------------------------------------------
    | Médias
    |--------------------------------------------------------------------------
    */

    public function bannerVideoUrl(): ?string
    {
        return $this->banner_video
            ? Storage::disk('public')->url($this->banner_video)
            : null;
    }


    public function bannerPosterUrl(): ?string
    {
        return $this->banner_poster
            ? Storage::disk('public')->url($this->banner_poster)
            : null;
    }


    public function storyImageUrl(): ?string
    {
        return $this->story_image
            ? Storage::disk('public')->url($this->story_image)
            : null;
    }


    public function hasActiveBannerVideo(): bool
    {
        return $this->banner_video_enabled
            && filled($this->banner_video);
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
