<?php

namespace App\Http\Requests\Admin\Reservations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'reservable_type' => ['required', 'in:conference,masterclass,coaching_session'],
            'reservable_id' => ['required', 'integer'],
            'status' => ['required', 'in:confirmed,pending,cancelled'],
        ];
    }
}
