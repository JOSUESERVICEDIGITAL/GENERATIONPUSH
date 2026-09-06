<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreEngagementApplicationRequest;
use App\Models\EngagementPage;
use App\Models\VolunteerApplication;
use App\Models\PartnerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EngagementController extends Controller
{
    /**
     * Formulaire partenaire.
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
     * Formulaire bénévole.
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
     * Enregistre une candidature.
     */
    public function store(StoreEngagementApplicationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | BÉNÉVOLE
        |--------------------------------------------------------------------------
        */
        if ($validated['type'] === 'volunteer') {

            VolunteerApplication::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'phone'        => $validated['phone'] ?? null,
                'profession'   => $validated['organization'] ?? null,
                'motivation'   => $validated['message'],
                'status'       => 'pending',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PARTENAIRE
        |--------------------------------------------------------------------------
        */
        elseif ($validated['type'] === 'partner') {

            PartnerApplication::create([
                'contact_name'        => $validated['name'],
                'email'               => $validated['email'],
                'phone'               => $validated['phone'] ?? null,
                'organization'        => $validated['organization'] ?? null,
                'collaboration_project' => $validated['message'],
                'status'              => 'pending',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Type invalide
        |--------------------------------------------------------------------------
        */
        else {
            abort(404);
        }

        return redirect()
            ->back()
            ->with(
                'success',
                'Merci pour ta candidature ! On te recontacte très vite.'
            );
    }
}

