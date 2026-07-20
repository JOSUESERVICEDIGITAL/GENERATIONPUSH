<?php

namespace App\Http\Controllers\Admin\Communications;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $subscribers = NewsletterSubscriber::query()
            ->when($search !== '', fn ($query) => $query->where('email', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => NewsletterSubscriber::where('status', 'subscribed')->count(),
        ];

        return view('admin.communications.subscribers.index', compact('subscribers', 'stats', 'search'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->back()->with('success', 'Abonné supprimé.');
    }
}
