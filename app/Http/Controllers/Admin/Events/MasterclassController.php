<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Masterclasses\StoreMasterclassRequest;
use App\Http\Requests\Admin\Masterclasses\UpdateMasterclassRequest;
use App\Models\Masterclass;
use Illuminate\Http\Request;

class MasterclassController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $masterclasses = Masterclass::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('speaker', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderBy('date')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Masterclass::count(),
            'upcoming' => Masterclass::where('status', 'upcoming')->count(),
            'registrations' => (int) Masterclass::sum('registered'),
            'avg_capacity' => (int) round(Masterclass::avg('capacity') ?? 0),
        ];

        return view('admin.events.masterclass.index', compact('masterclasses', 'stats', 'search', 'status'));
    }

    public function store(StoreMasterclassRequest $request)
    {
        Masterclass::create($request->validated());

        return redirect()
            ->route('admin.events.masterclass.index')
            ->with('success', 'Masterclass créée avec succès.');
    }

    public function update(UpdateMasterclassRequest $request, Masterclass $masterclass)
    {
        $masterclass->update($request->validated());

        return redirect()
            ->route('admin.events.masterclass.index')
            ->with('success', 'Masterclass mise à jour avec succès.');
    }

    public function destroy(Masterclass $masterclass)
    {
        $masterclass->delete();

        return redirect()
            ->route('admin.events.masterclass.index')
            ->with('success', 'Masterclass supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        Masterclass::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.events.masterclass.index')
            ->with('success', count($ids) . ' masterclass(es) supprimée(s).');
    }
}
