<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coaching\StoreCoachingRequest;
use App\Http\Requests\Admin\Coaching\UpdateCoachingRequest;
use App\Models\CoachingSession;
use Illuminate\Http\Request;

class CoachingController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $sessions = CoachingSession::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('coach', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderBy('date')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => CoachingSession::count(),
            'upcoming' => CoachingSession::where('status', 'upcoming')->count(),
            'registrations' => (int) CoachingSession::sum('registered'),
            'avg_capacity' => (int) round(CoachingSession::avg('capacity') ?? 0),
        ];

        return view('admin.events.coaching.index', compact('sessions', 'stats', 'search', 'status'));
    }

    public function store(StoreCoachingRequest $request)
    {
        CoachingSession::create($request->validated());

        return redirect()
            ->route('admin.events.coaching.index')
            ->with('success', 'Séance de coaching créée avec succès.');
    }

    public function update(UpdateCoachingRequest $request, CoachingSession $coaching)
    {
        $coaching->update($request->validated());

        return redirect()
            ->route('admin.events.coaching.index')
            ->with('success', 'Séance mise à jour avec succès.');
    }

    public function destroy(CoachingSession $coaching)
    {
        $coaching->delete();

        return redirect()
            ->route('admin.events.coaching.index')
            ->with('success', 'Séance supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        CoachingSession::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.events.coaching.index')
            ->with('success', count($ids) . ' séance(s) supprimée(s).');
    }
}
