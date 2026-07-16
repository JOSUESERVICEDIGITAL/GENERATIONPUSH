<?php

namespace App\Http\Requests\Admin\Masterclasses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMasterclassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'date' => ['nullable', 'date'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'registered' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
        ];
    }
}
