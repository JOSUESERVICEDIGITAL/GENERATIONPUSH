<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartnerApplicationRequest extends FormRequest
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
            'type' => ['required', Rule::in(['partner'])],

            // Informations sur l'organisation
            'organization' => ['required', 'string', 'max:255'],
            'sector' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],

            // Type de partenariat
            'partnership_types' => ['required', 'array', 'min:1'],
            'partnership_types.*' => [
                'string',
                Rule::in([
                    'event',
                    'content',
                    'institutional',
                    'sponsorship',
                ]),
            ],
            'partnership_other' => ['nullable', 'string', 'max:255'],

            // Projet
            'message' => ['required', 'string', 'max:5000'],

            // Budget
            'budget' => [
                'nullable',
                Rule::in([
                    'less_5000',
                    '5000_20000',
                    'more_20000',
                    'discuss',
                ]),
            ],

            // Informations complémentaires
            'website' => ['nullable', 'url', 'max:500'],

            // Découverte de Generation PUSH
            'discovery_source' => [
                'required',
                Rule::in([
                    'social_media',
                    'word_of_mouth',
                    'gp_event',
                    'other',
                ]),
            ],
            'discovery_other' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Validation supplémentaire.
     */
    public function withValidator($validator): void
    {
        $validator->sometimes(
            'discovery_other',
            ['required', 'string', 'max:255'],
            fn ($input) => $input->discovery_source === 'other'
        );
    }

    /**
     * Noms personnalisés des champs.
     */
    public function attributes(): array
    {
        return [
            'organization' => 'nom de l’organisation',
            'sector' => 'secteur d’activité',
            'name' => 'nom et prénom du contact',
            'position' => 'fonction ou poste',
            'email' => 'email professionnel',
            'phone' => 'numéro WhatsApp',
            'country' => 'pays',
            'partnership_types' => 'types de partenariat',
            'message' => 'projet de collaboration',
            'budget' => 'budget indicatif',
            'website' => 'site web',
            'discovery_source' => 'source de découverte de Generation PUSH',
        ];
    }
}
