<?php

namespace App\Http\Requests\Admin\Tickets;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticketable_type' => ['required', 'in:conference,masterclass,coaching_session'],
            'ticketable_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity_total' => ['required', 'integer', 'min:0'],
            'quantity_sold' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,sold_out,closed'],
        ];
    }
}
