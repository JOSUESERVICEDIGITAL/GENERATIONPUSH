<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'type' => ['required', 'in:book,video,usb_key'],
            'is_free' => ['nullable', 'boolean'],
            'price' => ['required_if:is_free,0', 'nullable', 'numeric', 'min:0'],
            'image' => ['required', 'image', 'max:4096'],
            'file' => ['nullable', 'file', 'max:51200'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
