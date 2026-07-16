<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreProductRequest;
use App\Http\Requests\Admin\Products\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $type = $request->query('type');
        $status = $request->query('status');

        $products = Product::query()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($type, fn ($query) => $query->where('type', $type))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Product::count(),
            'published' => Product::where('status', 'published')->count(),
            'free' => Product::where('is_free', true)->count(),
            'sales' => (int) Product::sum('sales'),
        ];

        return view('admin.shop.products.index', compact('products', 'stats', 'search', 'type', 'status'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['is_free'] = $request->boolean('is_free');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products/images', 'public');
        }

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('products/files', 'public');
        }

        Product::create($data);

        return redirect()
            ->route('admin.shop.products.index')
            ->with('success', 'Produit ajouté avec succès.');
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['is_free'] = $request->boolean('is_free');

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products/images', 'public');
        }

        if ($request->hasFile('file')) {
            if ($product->file_path) {
                Storage::disk('public')->delete($product->file_path);
            }
            $data['file_path'] = $request->file('file')->store('products/files', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('admin.shop.products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        if ($product->file_path) {
            Storage::disk('public')->delete($product->file_path);
        }

        $product->delete();

        return redirect()
            ->route('admin.shop.products.index')
            ->with('success', 'Produit supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $products = Product::whereIn('id', $ids)->get();

        foreach ($products as $product) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            if ($product->file_path) {
                Storage::disk('public')->delete($product->file_path);
            }
        }

        Product::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.shop.products.index')
            ->with('success', count($ids) . ' produit(s) supprimé(s).');
    }
}
