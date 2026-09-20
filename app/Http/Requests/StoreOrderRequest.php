<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | VERSION
            |--------------------------------------------------------------------------
            */

            'fulfillment_type' => [
                'required',
                'in:physical,digital',
            ],

            /*
            |--------------------------------------------------------------------------
            | QUANTITÉ
            |--------------------------------------------------------------------------
            */

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
            ],

            /*
            |--------------------------------------------------------------------------
            | NOTE
            |--------------------------------------------------------------------------
            */

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'fulfillment_type.required' =>
                'Veuillez choisir une version.',

            'fulfillment_type.in' =>
                'La version sélectionnée est invalide.',

            'quantity.required' =>
                'Veuillez indiquer une quantité.',

            'quantity.integer' =>
                'La quantité doit être un nombre entier.',

            'quantity.min' =>
                'La quantité minimale est de 1.',

            'quantity.max' =>
                'La quantité maximale est de 99.',

            'notes.string' =>
                'La note doit être un texte.',

            'notes.max' =>
                'Les notes ne peuvent pas dépasser 1000 caractères.',
        ];
    }
}
