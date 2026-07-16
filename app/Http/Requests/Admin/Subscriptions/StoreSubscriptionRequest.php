<?php

namespace App\Http\Requests\Admin\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'plan' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'status' => ['required', 'in:active,cancelled,expired'],
            'started_at' => ['nullable', 'date'],
            'next_billing_at' => ['nullable', 'date'],
        ];
    }
}
