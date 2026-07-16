<?php

namespace App\Http\Requests\Admin\Sponsors;

use Illuminate\Foundation\Http\FormRequest;

class StoreSponsorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'tier' => ['required', 'in:platinum,gold,silver,bronze'],
            'status' => ['required', 'in:active,inactive'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
