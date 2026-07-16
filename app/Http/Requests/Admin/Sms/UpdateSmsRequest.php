<?php

namespace App\Http\Requests\Admin\Sms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSmsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:160'],
            'audience' => ['required', 'in:all,members,leaders'],
            'status' => ['required', 'in:draft,scheduled,sent'],
            'scheduled_at' => ['nullable', 'date'],
        ];
    }
}
