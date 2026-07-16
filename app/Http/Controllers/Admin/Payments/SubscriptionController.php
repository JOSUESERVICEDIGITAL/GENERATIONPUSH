<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subscriptions\StoreSubscriptionRequest;
use App\Http\Requests\Admin\Subscriptions\UpdateSubscriptionRequest;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $subscriptions = Subscription::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Subscription::count(),
            'active' => Subscription::where('status', 'active')->count(),
            'mrr' => (float) Subscription::where('status', 'active')->where('billing_cycle', 'monthly')->sum('price')
                + (float) Subscription::where('status', 'active')->where('billing_cycle', 'yearly')->sum('price') / 12,
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
        ];

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.payments.subscriptions.index', compact('subscriptions', 'stats', 'search', 'status', 'users'));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        Subscription::create($request->validated());

        return redirect()
            ->route('admin.payments.subscriptions.index')
            ->with('success', 'Abonnement créé avec succès.');
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $subscription->update($request->validated());

        return redirect()
            ->route('admin.payments.subscriptions.index')
            ->with('success', 'Abonnement mis à jour avec succès.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()
            ->route('admin.payments.subscriptions.index')
            ->with('success', 'Abonnement supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        Subscription::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.payments.subscriptions.index')
            ->with('success', count($ids) . ' abonnement(s) supprimé(s).');
    }
}
