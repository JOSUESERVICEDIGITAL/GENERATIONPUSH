<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(
        ProfileUpdateRequest $request
    ): RedirectResponse {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS DU PROFIL
        |--------------------------------------------------------------------------
        */

        $user->fill([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'country' => $request->validated('country'),
            'city' => $request->validated('city'),
            'address' => $request->validated('address'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | EMAIL MODIFIÉ
        |--------------------------------------------------------------------------
        */

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        /*
        |--------------------------------------------------------------------------
        | PHOTO DE PROFIL
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_photo')) {

            // Supprimer l'ancienne photo si elle existe
            if (
                $user->profile_photo
                && Storage::disk('public')->exists($user->profile_photo)
            ) {
                Storage::disk('public')->delete(
                    $user->profile_photo
                );
            }

            $user->profile_photo = $request
                ->file('profile_photo')
                ->store('profile-photos', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | PROFIL COMPLET
        |--------------------------------------------------------------------------
        |
        | Le profil est considéré comme terminé uniquement si toutes les
        | informations nécessaires sont présentes.
        |
        */

        $profileIsComplete =
            filled($user->name)
            && filled($user->email)
            && filled($user->phone)
            && filled($user->country)
            && filled($user->city)
            && filled($user->address)
            && filled($user->profile_photo);

        if ($profileIsComplete) {
            $user->profile_completed_at ??= now();
        } else {
            $user->profile_completed_at = null;
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | RETOUR À LA PAGE DEMANDÉE
        |--------------------------------------------------------------------------
        |
        | Exemple :
        |
        | Produit
        |    ↓
        | Commander
        |    ↓
        | Connexion
        |    ↓
        | Profil incomplet
        |    ↓
        | Profil
        |    ↓
        | Formulaire de commande
        |
        | redirect()->intended() récupère l'URL enregistrée pendant
        | la tentative de connexion.
        |
        */

        if ($profileIsComplete && $request->session()->has('url.intended')) {
            return redirect()->intended(
                route('dashboard', absolute: false)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RETOUR NORMAL AU PROFIL
        |--------------------------------------------------------------------------
        */

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | SUPPRESSION DE LA PHOTO
        |--------------------------------------------------------------------------
        */

        if (
            $user->profile_photo
            && Storage::disk('public')->exists($user->profile_photo)
        ) {
            Storage::disk('public')->delete(
                $user->profile_photo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DÉCONNEXION
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
