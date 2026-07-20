<?php

namespace App\Http\Requests\Admin\MenuItems;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'exists:menu_items,id'],
            'label' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:255'],
            'location' => ['required', 'in:navbar,footer'],
            'footer_column' => ['nullable', 'string', 'max:100'],
            'open_in_new_tab' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
