<?php

namespace App\Http\Requests\Admin\Founder;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFounderProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'role_title' => ['nullable', 'string', 'max:255'],
            'main_photo' => ['nullable', 'image', 'max:6144'],

            'bio' => ['nullable', 'string', 'max:3000'],
            'why_founded' => ['nullable', 'string', 'max:3000'],
            'mission' => ['nullable', 'string', 'max:3000'],

            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],

            'show_bio' => ['nullable', 'boolean'],
            'show_why_founded' => ['nullable', 'boolean'],
            'show_mission' => ['nullable', 'boolean'],
            'show_social' => ['nullable', 'boolean'],
            'show_gallery' => ['nullable', 'boolean'],
            'is_page_enabled' => ['nullable', 'boolean'],
        ];
    }
}
