<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tickets\StoreTicketRequest;
use App\Http\Requests\Admin\Tickets\UpdateTicketRequest;
use App\Models\CoachingSession;
use App\Models\Conference;
use App\Models\Masterclass;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $type = $request->query('type');

        $tickets = Ticket::query()
            ->with('ticketable')
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($type, fn ($query) => $query->where('ticketable_type', $type))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Ticket::count(),
            'active' => Ticket::where('status', 'active')->count(),
            'sold' => (int) Ticket::sum('quantity_sold'),
            'revenue' => (float) Ticket::selectRaw('SUM(price * quantity_sold) as total')->value('total'),
        ];

        return view('admin.events.tickets.index', compact('tickets', 'stats', 'search', 'status', 'type')
            + $this->eventOptions());
    }

    public function store(StoreTicketRequest $request)
    {
        Ticket::create($request->validated());

        return redirect()
            ->route('admin.events.tickets.index')
            ->with('success', 'Billet créé avec succès.');
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->validated());

        return redirect()
            ->route('admin.events.tickets.index')
            ->with('success', 'Billet mis à jour avec succès.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()
            ->route('admin.events.tickets.index')
            ->with('success', 'Billet supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        Ticket::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.events.tickets.index')
            ->with('success', count($ids) . ' billet(s) supprimé(s).');
    }

    private function eventOptions(): array
    {
        return [
            'conferences' => Conference::orderBy('title')->get(['id', 'title']),
            'masterclasses' => Masterclass::orderBy('title')->get(['id', 'title']),
            'coachingSessions' => CoachingSession::orderBy('title')->get(['id', 'title']),
        ];
    }
}
