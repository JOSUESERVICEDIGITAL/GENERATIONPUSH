<?php

namespace App\Http\Controllers\Admin\Communications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sms\StoreSmsRequest;
use App\Http\Requests\Admin\Sms\UpdateSmsRequest;
use App\Models\SmsCampaign;
use App\Models\User;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $campaigns = SmsCampaign::query()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => SmsCampaign::count(),
            'sent' => SmsCampaign::where('status', 'sent')->count(),
            'draft' => SmsCampaign::where('status', 'draft')->count(),
            'recipients' => (int) SmsCampaign::where('status', 'sent')->sum('recipients_count'),
        ];

        return view('admin.communications.sms.index', compact('campaigns', 'stats', 'search', 'status'));
    }

    public function store(StoreSmsRequest $request)
    {
        SmsCampaign::create($request->validated());

        return redirect()
            ->route('admin.communications.sms.index')
            ->with('success', 'Campagne SMS créée avec succès.');
    }

    public function update(UpdateSmsRequest $request, SmsCampaign $sm)
    {
        $sm->update($request->validated());

        return redirect()
            ->route('admin.communications.sms.index')
            ->with('success', 'Campagne SMS mise à jour avec succès.');
    }

    public function destroy(SmsCampaign $sm)
    {
        $sm->delete();

        return redirect()
            ->route('admin.communications.sms.index')
            ->with('success', 'Campagne SMS supprimée.');
    }

    public function send(SmsCampaign $sm)
    {
        // NOTE: marque la campagne comme envoyée. Un envoi réel nécessiterait
        // l'intégration d'une passerelle SMS (Orange SMS API, Twilio, etc.)
        // avec les identifiants correspondants côté .env.
        $sm->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipients_count' => $this->audienceCount($sm->audience),
        ]);

        return redirect()
            ->route('admin.communications.sms.index')
            ->with('success', 'Campagne SMS marquée comme envoyée (' . $sm->recipients_count . ' destinataires).');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        SmsCampaign::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.communications.sms.index')
            ->with('success', count($ids) . ' campagne(s) supprimée(s).');
    }

    private function audienceCount(string $audience): int
    {
        return match ($audience) {
            'members' => User::where('role', 'Member')->count(),
            'leaders' => User::where('role', 'Leader')->count(),
            default => User::count(),
        };
    }
}
