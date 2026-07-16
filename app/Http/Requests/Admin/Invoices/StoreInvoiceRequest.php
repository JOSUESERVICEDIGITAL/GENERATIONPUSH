<?php

namespace App\Http\Requests\Admin\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:paid,pending,overdue'],
            'issued_at' => ['required', 'date'],
            'due_at' => ['nullable', 'date'],
        ];
    }
}
