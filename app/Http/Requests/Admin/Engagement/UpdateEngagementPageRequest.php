<?php

namespace App\Http\Requests\Admin\Engagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEngagementPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:3000'],
            'benefits' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:6144'],
            'cta_label' => ['nullable', 'string', 'max:100'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }
}
