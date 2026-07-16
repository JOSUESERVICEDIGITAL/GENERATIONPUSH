<?php

namespace App\Http\Requests\Admin\Library;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibraryResourceRequest extends FormRequest
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
            'type' => ['required', 'in:pdf,doc,video,link'],
            'url' => ['required', 'url', 'max:500'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
