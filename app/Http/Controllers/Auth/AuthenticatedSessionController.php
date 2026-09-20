<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | URL DE REDIRECTION APRÈS CONNEXION
        |--------------------------------------------------------------------------
        |
        | Exemple :
        | /login?redirect=/boutique/commande/mon-produit
        |
        | On enregistre cette URL dans la session afin que
        | redirect()->intended() puisse la récupérer après connexion.
        |
        */

        if ($request->filled('redirect')) {
            $redirect = $request->string('redirect')->toString();

            /*
            | On accepte uniquement les URLs internes à l'application.
            | Cela évite qu'un utilisateur puisse utiliser le paramètre
            | redirect pour envoyer quelqu'un vers un site externe.
            */
            if (
                str_starts_with($redirect, '/')
                && ! str_starts_with($redirect, '//')
            ) {
                $request->session()->put('url.intended', $redirect);
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | COMPTE EN ATTENTE / INACTIF
        |--------------------------------------------------------------------------
        |
        | Un membre dont le compte n'est pas encore actif ne peut pas accéder
        | à l'espace membre.
        |
        */

        if (
            $user->role === 'Member'
            && $user->status !== 'active'
        ) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with(
                    'pending',
                    'Votre inscription au Hub est bien enregistrée. '
                    . 'Votre compte est actuellement en attente de validation '
                    . 'par notre équipe. Nous vous contacterons dès que votre '
                    . 'demande aura été traitée.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | RÉGÉNÉRATION DE LA SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | PROFIL MEMBRE À COMPLÉTER
        |--------------------------------------------------------------------------
        |
        | Tout membre validé doit compléter son profil avant de continuer.
        |
        | Si l'utilisateur venait de la boutique, l'URL de commande est déjà
        | enregistrée dans url.intended.
        |
        */

        if (
            $user->role === 'Member'
            && $user->status === 'active'
            && $user->needsProfileCompletion()
        ) {
            return redirect()
                ->route('profile.edit')
                ->with(
                    'profile_required',
                    'Bienvenue dans Generation PUSH ! '
                    . 'Avant de continuer, veuillez compléter votre profil. '
                    . 'Ces informations seront notamment utilisées pour vos '
                    . 'futures commandes et livraisons.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | ACCÈS NORMAL / RETOUR À LA PAGE DEMANDÉE
        |--------------------------------------------------------------------------
        */

        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
