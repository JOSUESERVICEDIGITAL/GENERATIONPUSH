<?php

namespace App\Http\Controllers\Admin\Communications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notifications\StoreNotificationRequest;
use App\Http\Requests\Admin\Notifications\UpdateNotificationRequest;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $campaigns = AdminNotification::query()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => AdminNotification::count(),
            'sent' => AdminNotification::where('status', 'sent')->count(),
            'draft' => AdminNotification::where('status', 'draft')->count(),
            'recipients' => (int) AdminNotification::where('status', 'sent')->sum('recipients_count'),
        ];

        return view('admin.communications.notifications.index', compact('campaigns', 'stats', 'search', 'status'));
    }

    public function store(StoreNotificationRequest $request)
    {
        AdminNotification::create($request->validated());

        return redirect()
            ->route('admin.communications.notifications.index')
            ->with('success', 'Notification créée avec succès.');
    }

    public function update(UpdateNotificationRequest $request, AdminNotification $notification)
    {
        $notification->update($request->validated());

        return redirect()
            ->route('admin.communications.notifications.index')
            ->with('success', 'Notification mise à jour avec succès.');
    }

    public function destroy(AdminNotification $notification)
    {
        $notification->delete();

        return redirect()
            ->route('admin.communications.notifications.index')
            ->with('success', 'Notification supprimée.');
    }

    public function send(AdminNotification $notification)
    {
        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipients_count' => $this->audienceCount($notification->audience),
        ]);

        return redirect()
            ->route('admin.communications.notifications.index')
            ->with('success', 'Notification envoyée (' . $notification->recipients_count . ' destinataires).');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        AdminNotification::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.communications.notifications.index')
            ->with('success', count($ids) . ' notification(s) supprimée(s).');
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
