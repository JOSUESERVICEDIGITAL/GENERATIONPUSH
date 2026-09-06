<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class StoreEngagementApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | COMMUN
            |--------------------------------------------------------------------------
            */

            'type' => [
                'required',
                'in:partner,volunteer',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:255',
            ],

            'message' => [
                'nullable',
                'string',
                'max:2000',
            ],

            /*
            |--------------------------------------------------------------------------
            | BÉNÉVOLE
            |--------------------------------------------------------------------------
            */

            'city_country' => [
                'required_if:type,volunteer',
                'nullable',
                'string',
                'max:255',
            ],

            'age' => [
                'required_if:type,volunteer',
                'nullable',
                'integer',
                'min:15',
                'max:120',
            ],

            'profession' => [
                'required_if:type,volunteer',
                'nullable',
                'string',
                'max:255',
            ],

            'contribution_areas' => [
                'required_if:type,volunteer',
                'nullable',
                'array',
                'min:1',
            ],

            'contribution_areas.*' => [
                'string',
                'max:255',
            ],

            'contribution_other' => [
                'nullable',
                'string',
                'max:255',
            ],

            'motivation' => [
                'required_if:type,volunteer',
                'nullable',
                'string',
                'max:5000',
            ],

            'skills' => [
                'required_if:type,volunteer',
                'nullable',
                'string',
                'max:5000',
            ],

            'availability' => [
                'required_if:type,volunteer',
                'nullable',
                'in:part_time,events_only,full_time',
            ],

            'participated_before' => [
                'required_if:type,volunteer',
                'nullable',
                'in:yes,no',
            ],

            'social_link' => [
                'nullable',
                'url',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | PARTENAIRE
            |--------------------------------------------------------------------------
            */

            'organization' => [
                'required_if:type,partner',
                'nullable',
                'string',
                'max:255',
            ],

            'sector' => [
                'required_if:type,partner',
                'nullable',
                'string',
                'max:255',
            ],

            'position' => [
                'required_if:type,partner',
                'nullable',
                'string',
                'max:255',
            ],

            'country' => [
                'required_if:type,partner',
                'nullable',
                'string',
                'max:255',
            ],

            'partnership_types' => [
                'required_if:type,partner',
                'nullable',
                'array',
                'min:1',
            ],

            'partnership_types.*' => [
                'string',
                'max:255',
            ],

            'partnership_other' => [
                'nullable',
                'string',
                'max:255',
            ],

            'message' => [
                'required_if:type,partner',
                'nullable',
                'string',
                'max:5000',
            ],

            'budget' => [
                'nullable',
                'in:less_5000,5000_20000,more_20000,discuss',
            ],

            'website' => [
                'nullable',
                'url',
                'max:255',
            ],

            'discovery_source' => [
                'required_if:type,partner',
                'nullable',
                'in:social_media,word_of_mouth,gp_event,other',
            ],

            'discovery_other' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | FICHIER
            |--------------------------------------------------------------------------
            */

            'document' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Le type de candidature est obligatoire.',
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L’adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'phone.required' => 'Le numéro WhatsApp est obligatoire.',

            'city_country.required_if' =>
                'La ville et le pays sont obligatoires.',

            'age.required_if' =>
                'L’âge est obligatoire.',

            'age.min' =>
                'L’âge minimum est de 15 ans.',

            'profession.required_if' =>
                'Le domaine d’études ou la profession est obligatoire.',

            'contribution_areas.required_if' =>
                'Sélectionnez au moins un domaine de contribution.',

            'contribution_areas.min' =>
                'Sélectionnez au moins un domaine de contribution.',

            'motivation.required_if' =>
                'La motivation est obligatoire.',

            'skills.required_if' =>
                'Veuillez indiquer ce que vous pouvez apporter.',

            'availability.required_if' =>
                'Veuillez sélectionner votre disponibilité.',

            'participated_before.required_if' =>
                'Veuillez indiquer si vous avez déjà participé à un événement.',

            'organization.required_if' =>
                'Le nom de l’organisation est obligatoire.',

            'sector.required_if' =>
                'Le secteur d’activité est obligatoire.',

            'position.required_if' =>
                'La fonction ou le poste est obligatoire.',

            'country.required_if' =>
                'Le pays est obligatoire.',

            'partnership_types.required_if' =>
                'Sélectionnez au moins un type de partenariat.',

            'partnership_types.min' =>
                'Sélectionnez au moins un type de partenariat.',

            'message.required_if' =>
                'Décrivez votre projet de collaboration.',

            'discovery_source.required_if' =>
                'Veuillez indiquer comment vous avez connu Generation PUSH.',

            'document.file' =>
                'Le fichier envoyé est invalide.',

            'document.mimes' =>
                'Le fichier doit être au format PDF, DOC ou DOCX.',

            'document.max' =>
                'Le fichier ne doit pas dépasser 10 Mo.',
        ];
    }
}
