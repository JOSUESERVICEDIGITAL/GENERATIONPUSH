<?php

namespace App\Http\Requests\Admin\Videos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo', 'max:102400'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'duration' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
