<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $type = $request->query('type');

        $products = Product::query()
            ->where('status', 'published')
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($type, fn ($query) => $query->where('type', $type))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('front.shop.index', compact('products', 'search', 'type'));
    }

    public function show(Product $product)
    {
        abort_if($product->status !== 'published', 404);

        $related = Product::where('status', 'published')
            ->where('id', '!=', $product->id)
            ->where('type', $product->type)
            ->take(3)
            ->get();

        return view('front.shop.show', compact('product', 'related'));
    }
}
