<?php

namespace App\Http\Controllers\Admin\Programs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Library\StoreLibraryResourceRequest;
use App\Http\Requests\Admin\Library\UpdateLibraryResourceRequest;
use App\Models\LibraryResource;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $type = $request->query('type');

        $resources = LibraryResource::query()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($type, fn ($query) => $query->where('type', $type))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => LibraryResource::count(),
            'published' => LibraryResource::where('status', 'published')->count(),
            'downloads' => (int) LibraryResource::sum('downloads'),
        ];

        return view('admin.programs.library.index', compact('resources', 'stats', 'search', 'type'));
    }

    public function store(StoreLibraryResourceRequest $request)
    {
        LibraryResource::create($request->validated());

        return redirect()
            ->route('admin.programs.library.index')
            ->with('success', 'Ressource ajoutée avec succès.');
    }

    public function update(UpdateLibraryResourceRequest $request, LibraryResource $resource)
    {
        $resource->update($request->validated());

        return redirect()
            ->route('admin.programs.library.index')
            ->with('success', 'Ressource mise à jour avec succès.');
    }

    public function destroy(LibraryResource $resource)
    {
        $resource->delete();

        return redirect()
            ->route('admin.programs.library.index')
            ->with('success', 'Ressource supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        LibraryResource::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.programs.library.index')
            ->with('success', count($ids) . ' ressource(s) supprimée(s).');
    }
}
