<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreEngagementApplicationRequest;
use App\Models\EngagementPage;
use App\Models\PartnerApplication;
use App\Models\VolunteerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EngagementController extends Controller
{
    /**
     * Afficher le formulaire partenaire.
     */
    public function partner(): View
    {
        $page = EngagementPage::forType('partner');

        abort_unless($page && $page->is_visible, 404);

        return view('front.engagement', [
            'page' => $page,
            'type' => 'partner',
        ]);
    }

    /**
     * Afficher le formulaire bénévole.
     */
    public function volunteer(): View
    {
        $page = EngagementPage::forType('volunteer');

        abort_unless($page && $page->is_visible, 404);

        return view('front.engagement', [
            'page' => $page,
            'type' => 'volunteer',
        ]);
    }

    /**
     * Afficher la page de confirmation après une soumission.
     */
    public function success(string $type): View
    {
        abort_unless(
            in_array($type, ['hub', 'partner', 'volunteer'], true),
            404
        );

        return view('front.engagements.success', [
            'type' => $type,
            'name' => request()->query('name'),
            'email' => request()->query('email'),
            'organization' => request()->query('organization'),
        ]);
    }

    /**
     * Enregistrer une candidature.
     */
    public function store(
        StoreEngagementApplicationRequest $request
    ): RedirectResponse {

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | FICHIER
        |--------------------------------------------------------------------------
        */

        $documentPath = null;
        $documentName = null;

        if ($request->hasFile('document')) {
            $file = $request->file('document');

            $documentName = $file->getClientOriginalName();

            $folder = $data['type'] === 'partner'
                ? 'engagement/partners'
                : 'engagement/volunteers';

            $documentPath = $file->store($folder, 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | BÉNÉVOLE
        |--------------------------------------------------------------------------
        */

        if ($data['type'] === 'volunteer') {

            VolunteerApplication::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'city_country' => $data['city_country'],
                'age' => $data['age'],
                'profession' => $data['profession'],

                'contribution_areas' => json_encode(
                    $data['contribution_areas'],
                    JSON_UNESCAPED_UNICODE
                ),

                'contribution_other' =>
                $data['contribution_other'] ?? null,

                'motivation' => $data['motivation'],
                'skills' => $data['skills'],
                'availability' => $data['availability'],

                'participated_before' =>
                $data['participated_before'] === 'yes',

                'social_link' =>
                $data['social_link'] ?? null,

                'document_path' => $documentPath,
                'document_name' => $documentName,

                'status' => 'pending',
            ]);

            return redirect()->route(
                'front.engagement.success',
                [
                    'type' => 'volunteer',
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PARTENAIRE
        |--------------------------------------------------------------------------
        */

        if ($data['type'] === 'partner') {

            PartnerApplication::create([
                'organization' => $data['organization'],
                'sector' => $data['sector'],
                'contact_name' => $data['name'],
                'position' => $data['position'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country' => $data['country'],

                'partnership_types' => json_encode(
                    $data['partnership_types'],
                    JSON_UNESCAPED_UNICODE
                ),

                'partnership_other' =>
                $data['partnership_other'] ?? null,

                'collaboration_project' => $data['message'],

                'budget' =>
                $data['budget'] ?? null,

                'website' =>
                $data['website'] ?? null,

                'discovery_source' =>
                $data['discovery_source'],

                'discovery_other' =>
                $data['discovery_other'] ?? null,

                'document_path' => $documentPath,
                'document_name' => $documentName,

                'status' => 'pending',
            ]);

            return redirect()->route(
                'front.engagement.success',
                [
                    'type' => 'partner',
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'organization' => $data['organization'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TYPE INVALIDE
        |--------------------------------------------------------------------------
        */

        abort(404);
    }
}
