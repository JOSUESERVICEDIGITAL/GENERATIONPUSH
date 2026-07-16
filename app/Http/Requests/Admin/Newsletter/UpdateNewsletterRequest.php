<?php

namespace App\Http\Requests\Admin\Newsletter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'audience' => ['required', 'in:all,members,leaders'],
            'status' => ['required', 'in:draft,scheduled,sent'],
            'scheduled_at' => ['nullable', 'date'],
        ];
    }
}
