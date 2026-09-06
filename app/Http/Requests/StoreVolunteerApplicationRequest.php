<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVolunteerApplicationRequest extends FormRequest
{
    /**
     * Autoriser l'envoi du formulaire.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['volunteer', 'benevole'])],

            // Informations personnelles
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'city_country' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'profession' => ['required', 'string', 'max:255'],

            // Domaines de contribution
            'contribution_areas' => ['required', 'array', 'min:1'],
            'contribution_areas.*' => ['string', 'max:255'],
            'contribution_other' => ['nullable', 'string', 'max:255'],

            // Motivation
            'motivation' => ['required', 'string', 'max:5000'],
            'skills' => ['required', 'string', 'max:5000'],

            // Disponibilité
            'availability' => [
                'required',
                Rule::in(['part_time', 'events_only', 'full_time']),
            ],

            // Expérience
            'participated_before' => [
                'required',
                Rule::in(['yes', 'no']),
            ],

            // Réseau social
            'social_link' => ['nullable', 'url', 'max:500'],
        ];
    }

    /**
     * Noms personnalisés des champs.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nom complet',
            'email' => 'adresse email',
            'phone' => 'numéro WhatsApp',
            'city_country' => 'ville et pays de résidence',
            'age' => 'âge',
            'profession' => 'domaine d’études ou profession',
            'contribution_areas' => 'domaines de contribution',
            'motivation' => 'motivation',
            'skills' => 'présentation',
            'availability' => 'disponibilité',
            'participated_before' => 'participation à un événement Generation PUSH',
            'social_link' => 'lien LinkedIn ou réseau social',
        ];
    }
}
