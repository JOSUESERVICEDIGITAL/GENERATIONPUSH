<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reservations\StoreReservationRequest;
use App\Http\Requests\Admin\Reservations\UpdateReservationRequest;
use App\Models\CoachingSession;
use App\Models\Conference;
use App\Models\Masterclass;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $type = $request->query('type');

        $reservations = Reservation::query()
            ->with(['user', 'reservable'])
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($type, fn ($query) => $query->where('reservable_type', $type))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Reservation::count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
        ];

        return view('admin.events.reservations.index', compact('reservations', 'stats', 'search', 'status', 'type')
            + $this->eventOptions());
    }

    public function store(StoreReservationRequest $request)
    {
        Reservation::create($request->validated());

        return redirect()
            ->route('admin.events.reservations.index')
            ->with('success', 'Réservation créée avec succès.');
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $reservation->update($request->validated());

        return redirect()
            ->route('admin.events.reservations.index')
            ->with('success', 'Réservation mise à jour avec succès.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()
            ->route('admin.events.reservations.index')
            ->with('success', 'Réservation supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        Reservation::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.events.reservations.index')
            ->with('success', count($ids) . ' réservation(s) supprimée(s).');
    }

    /**
     * Données partagées avec la vue pour peupler les selects (utilisateurs + événements par type).
     */
    private function eventOptions(): array
    {
        return [
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
            'conferences' => Conference::orderBy('title')->get(['id', 'title']),
            'masterclasses' => Masterclass::orderBy('title')->get(['id', 'title']),
            'coachingSessions' => CoachingSession::orderBy('title')->get(['id', 'title']),
        ];
    }
}
