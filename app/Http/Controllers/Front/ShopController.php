<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Page publique de la Boutique.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Contenu administrable de la page Boutique
        |--------------------------------------------------------------------------
        */

        $page = Page::query()
            ->where('key', 'shop')
            ->where('status', 'active')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Recherche / filtre
        |--------------------------------------------------------------------------
        */

        $search = trim((string) $request->query('search'));
        $type = $request->query('type');


        /*
        |--------------------------------------------------------------------------
        | Produits publiés
        |--------------------------------------------------------------------------
        */

        $products = Product::query()
            ->where('status', 'published')

            ->when(
                $search !== '',
                fn ($query) =>
                    $query->where(
                        'title',
                        'like',
                        "%{$search}%"
                    )
            )

            ->when(
                $type,
                fn ($query) =>
                    $query->where(
                        'type',
                        $type
                    )
            )

            ->latest()
            ->paginate(9)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Vue
        |--------------------------------------------------------------------------
        */

        return view(
            'front.shop.index',
            compact(
                'page',
                'products',
                'search',
                'type'
            )
        );
    }


    /**
     * Détail d'un produit.
     */
    public function show(Product $product)
    {
        abort_if(
            $product->status !== 'published',
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Produits similaires
        |--------------------------------------------------------------------------
        */

        $related = Product::query()
            ->where('status', 'published')
            ->where('id', '!=', $product->id)
            ->where('type', $product->type)
            ->take(3)
            ->get();


        return view(
            'front.shop.show',
            compact(
                'product',
                'related'
            )
        );
    }
}