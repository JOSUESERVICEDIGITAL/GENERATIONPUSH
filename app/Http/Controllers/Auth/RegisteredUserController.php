<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Afficher le formulaire d'inscription.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Enregistrer une nouvelle demande d'inscription au Hub.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

            /*
            |--------------------------------------------------------------------------
            | NOUVELLE INSCRIPTION HUB
            |--------------------------------------------------------------------------
            |
            | Le compte est créé comme membre mais reste en attente
            | de validation par l'administrateur.
            |
            */

            'role' => 'Member',
            'status' => 'inactive',
        ]);

        event(new Registered($user));

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | On ne connecte PAS automatiquement le nouvel inscrit.
        | Son compte doit d'abord être validé par un administrateur.
        |
        */

        return redirect()
            ->route('registration.pending')
            ->with('registration_user', [
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'hub',
            ]);
    }
}
