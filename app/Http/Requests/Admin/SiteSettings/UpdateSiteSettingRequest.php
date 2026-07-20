<?php

namespace App\Http\Requests\Admin\SiteSettings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string', 'max:1000'],
            'hero_video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'hero_video_url' => ['nullable', 'url', 'max:500'],
            'hero_poster' => ['nullable', 'image', 'max:4096'],
            'hero_cta_label' => ['nullable', 'string', 'max:100'],
            'hero_cta_url' => ['nullable', 'string', 'max:255'],

            'about_title' => ['nullable', 'string', 'max:255'],
            'about_text' => ['nullable', 'string', 'max:2000'],
            'about_image' => ['nullable', 'image', 'max:4096'],

            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:255'],

            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],

            'newsletter_title' => ['nullable', 'string', 'max:255'],
            'newsletter_text' => ['nullable', 'string', 'max:500'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
