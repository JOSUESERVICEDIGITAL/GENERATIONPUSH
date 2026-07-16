<?php

namespace App\Http\Requests\Admin\Coaching;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoachingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'coach' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:100'],
            'date' => ['nullable', 'date'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'registered' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
        ];
    }
}
