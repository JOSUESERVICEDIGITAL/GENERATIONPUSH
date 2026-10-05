<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\LibraryResource;

class BookController extends Controller
{
    /**
     * Page publique des livres Generation PUSH.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | LIVRE PRINCIPAL DU PLATEAU
        |--------------------------------------------------------------------------
        |
        | On récupère uniquement un livre :
        | - publié
        | - sélectionné pour le plateau
        | - avec son Product associé
        |
        */

        $featuredBook = LibraryResource::query()
            ->with('product')
            ->where('status', 'published')
            ->where('show_on_podium', true)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | SÉCURITÉ / FALLBACK
        |--------------------------------------------------------------------------
        |
        | Si aucun livre n'a été explicitement placé sur le plateau,
        | on prend le premier livre publié selon l'ordre défini.
        |
        */

        if (! $featuredBook) {
            $featuredBook = LibraryResource::query()
                ->with('product')
                ->where('status', 'published')
                ->orderBy('display_order')
                ->latest('id')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | AUTRES LIVRES
        |--------------------------------------------------------------------------
        |
        | Le livre principal est exclu de cette sélection.
        |
        */

        $otherBooks = LibraryResource::query()
            ->with('product')
            ->where('status', 'published')
            ->when(
                $featuredBook,
                fn ($query) => $query->whereKeyNot($featuredBook->id)
            )
            ->orderByDesc('is_bestseller')
            ->orderBy('display_order')
            ->latest('id')
            ->get();

        return view('front.books.index', compact(
            'featuredBook',
            'otherBooks'
        ));
    }
}