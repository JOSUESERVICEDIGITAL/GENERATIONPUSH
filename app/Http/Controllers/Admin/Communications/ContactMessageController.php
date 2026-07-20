<?php

namespace App\Http\Controllers\Admin\Communications;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $messages = ContactMessage::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
        ];

        return view('admin.communications.messages.index', compact('messages', 'stats', 'search', 'status'));
    }

    public function markRead(ContactMessage $message)
    {
        $message->update(['status' => 'read']);

        return redirect()->back()->with('success', 'Message marqué comme lu.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->back()->with('success', 'Message supprimé.');
    }
}
