<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) ($this->user()?->chat_enabled);
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Écris un message avant d\'envoyer.',
        ];
    }
}
