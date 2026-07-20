<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuItems\StoreMenuItemRequest;
use App\Http\Requests\Admin\MenuItems\UpdateMenuItemRequest;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function navigation()
    {
        $items = MenuItem::where('location', 'navbar')
            ->with(['children' => fn ($q) => $q->orderBy('order')])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        $topLevelItems = MenuItem::where('location', 'navbar')->orderBy('order')->get(['id', 'label']);

        return view('admin.pages.navigation.index', compact('items', 'topLevelItems'));
    }

    public function footer()
    {
        $items = MenuItem::where('location', 'footer')->orderBy('order')->get()->groupBy(fn ($item) => $item->footer_column ?? 'Sans colonne');

        return view('admin.pages.footer.index', compact('items'));
    }

    public function store(StoreMenuItemRequest $request)
    {
        MenuItem::create($request->validated());

        return redirect()
            ->back()
            ->with('success', 'Lien ajouté avec succès.');
    }

    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem)
    {
        $menuItem->update($request->validated());

        return redirect()
            ->back()
            ->with('success', 'Lien mis à jour avec succès.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return redirect()
            ->back()
            ->with('success', 'Lien supprimé.');
    }
}
