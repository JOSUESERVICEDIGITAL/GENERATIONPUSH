<?php

namespace App\Http\Controllers\Admin\Communications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Newsletter\StoreNewsletterRequest;
use App\Http\Requests\Admin\Newsletter\UpdateNewsletterRequest;
use App\Models\NewsletterCampaign;
use App\Models\User;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $campaigns = NewsletterCampaign::query()
            ->when($search !== '', fn ($query) => $query->where('subject', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => NewsletterCampaign::count(),
            'sent' => NewsletterCampaign::where('status', 'sent')->count(),
            'draft' => NewsletterCampaign::where('status', 'draft')->count(),
            'recipients' => (int) NewsletterCampaign::where('status', 'sent')->sum('recipients_count'),
        ];

        return view('admin.communications.newsletter.index', compact('campaigns', 'stats', 'search', 'status'));
    }

    public function store(StoreNewsletterRequest $request)
    {
        NewsletterCampaign::create($request->validated());

        return redirect()
            ->route('admin.communications.newsletter.index')
            ->with('success', 'Campagne créée avec succès.');
    }

    public function update(UpdateNewsletterRequest $request, NewsletterCampaign $newsletter)
    {
        $newsletter->update($request->validated());

        return redirect()
            ->route('admin.communications.newsletter.index')
            ->with('success', 'Campagne mise à jour avec succès.');
    }

    public function destroy(NewsletterCampaign $newsletter)
    {
        $newsletter->delete();

        return redirect()
            ->route('admin.communications.newsletter.index')
            ->with('success', 'Campagne supprimée.');
    }

    public function send(NewsletterCampaign $newsletter)
    {
        // NOTE: marque la campagne comme envoyée dans le back-office.
        // Aucun email n'est réellement expédié : il faudrait brancher ici
        // un Mailable + Mail::to(...)->queue(...) avec un SMTP configuré
        // dans .env (MAIL_MAILER, MAIL_HOST, etc.).
        $newsletter->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipients_count' => $this->audienceCount($newsletter->audience),
        ]);

        return redirect()
            ->route('admin.communications.newsletter.index')
            ->with('success', 'Campagne marquée comme envoyée (' . $newsletter->recipients_count . ' destinataires).');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        NewsletterCampaign::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.communications.newsletter.index')
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
