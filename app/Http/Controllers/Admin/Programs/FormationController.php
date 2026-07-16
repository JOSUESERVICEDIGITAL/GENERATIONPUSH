<?php

namespace App\Http\Controllers\Admin\Programs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Formations\StoreFormationRequest;
use App\Http\Requests\Admin\Formations\UpdateFormationRequest;
use App\Models\Formation;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $formations = Formation::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('trainer', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Formation::count(),
            'active' => Formation::where('status', 'active')->count(),
            'participants' => (int) Formation::sum('participants'),
            'revenue' => (float) Formation::where('status', '!=', 'draft')->sum('price'),
        ];

        return view('admin.programs.formations.index', compact('formations', 'stats', 'search', 'status'));
    }

    public function store(StoreFormationRequest $request)
    {
        Formation::create($request->validated());

        return redirect()
            ->route('admin.programs.formations.index')
            ->with('success', 'Formation créée avec succès.');
    }

    public function update(UpdateFormationRequest $request, Formation $formation)
    {
        $formation->update($request->validated());

        return redirect()
            ->route('admin.programs.formations.index')
            ->with('success', 'Formation mise à jour avec succès.');
    }

    public function destroy(Formation $formation)
    {
        $formation->delete();

        return redirect()
            ->route('admin.programs.formations.index')
            ->with('success', 'Formation supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        Formation::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.programs.formations.index')
            ->with('success', count($ids) . ' formation(s) supprimée(s).');
    }
}
