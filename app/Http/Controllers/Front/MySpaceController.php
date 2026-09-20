<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class MySpaceController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role === 'Admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'Member' && $user->status === 'active') {
            return redirect()->route('member.dashboard');
        }

        abort(403, 'Votre compte ne dispose pas encore d’un espace accessible.');
    }
}