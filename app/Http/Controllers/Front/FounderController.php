<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\FounderProfile;

class FounderController extends Controller
{
    public function show()
    {
        $founder = FounderProfile::current();

        // Toutes les photos de la galerie (triées par ordre), si activée.
        $photoUrls = $founder->show_gallery
            ? $founder->photos()->orderBy('order')->get()->map(fn ($p) => $p->imageUrl())->filter()->values()
            : collect();

        // Si aucune photo de galerie, on retombe sur la photo principale seule.
        if ($photoUrls->isEmpty() && $founder->mainPhotoUrl()) {
            $photoUrls = collect([$founder->mainPhotoUrl()]);
        }

        return view('front.founder', compact('founder', 'photoUrls'));
    }
}
