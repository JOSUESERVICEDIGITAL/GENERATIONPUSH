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
            'description' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'cta_label' => ['nullable', 'string', 'max:255'],

            'is_visible' => ['nullable', 'boolean'],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'form_content' => ['nullable', 'array'],
        ];
    }
}
