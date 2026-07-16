<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Conferences\StoreConferenceRequest;
use App\Http\Requests\Admin\Conferences\UpdateConferenceRequest;
use App\Models\Conference;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $conferences = Conference::query()
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

        $featured = Conference::orderBy('date')->take(3)->get();

        $stats = [
            'total' => Conference::count(),
            'upcoming' => Conference::where('status', 'upcoming')->count(),
            'registrations' => (int) Conference::sum('registered'),
            'avg_capacity' => (int) round(Conference::avg('capacity') ?? 0),
        ];

        return view('admin.events.conferences.index', compact('conferences', 'featured', 'stats', 'search', 'status'));
    }

    public function store(StoreConferenceRequest $request)
    {
        Conference::create($request->validated());

        return redirect()
            ->route('admin.events.conferences.index')
            ->with('success', 'Conférence créée avec succès.');
    }

    public function update(UpdateConferenceRequest $request, Conference $conference)
    {
        $conference->update($request->validated());

        return redirect()
            ->route('admin.events.conferences.index')
            ->with('success', 'Conférence mise à jour avec succès.');
    }

    public function destroy(Conference $conference)
    {
        $conference->delete();

        return redirect()
            ->route('admin.events.conferences.index')
            ->with('success', 'Conférence supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        Conference::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.events.conferences.index')
            ->with('success', count($ids) . ' conférence(s) supprimée(s).');
    }
}
