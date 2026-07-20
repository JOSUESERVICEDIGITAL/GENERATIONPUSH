<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomPages\StoreCustomPageRequest;
use App\Http\Requests\Admin\CustomPages\UpdateCustomPageRequest;
use App\Models\CustomPage;
use Illuminate\Http\Request;

class CustomPageController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $pages = CustomPage::query()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.custom.index', compact('pages', 'search'));
    }

    public function create()
    {
        return view('admin.pages.custom.create');
    }

    public function store(StoreCustomPageRequest $request)
    {
        CustomPage::create($request->validated());

        return redirect()
            ->route('admin.pages.custom.index')
            ->with('success', 'Page créée avec succès.');
    }

    public function edit(CustomPage $custom)
    {
        return view('admin.pages.custom.edit', ['page' => $custom]);
    }

    public function update(UpdateCustomPageRequest $request, CustomPage $custom)
    {
        $custom->update($request->validated());

        return redirect()
            ->route('admin.pages.custom.index')
            ->with('success', 'Page mise à jour avec succès.');
    }

    public function destroy(CustomPage $custom)
    {
        $custom->delete();

        return redirect()
            ->route('admin.pages.custom.index')
            ->with('success', 'Page supprimée.');
    }
}
