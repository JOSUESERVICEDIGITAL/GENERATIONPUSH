<?php

namespace App\Http\Controllers\Admin\Communications;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $threadUserIds = ChatMessage::query()->distinct()->pluck('user_id');

        $threads = User::query()
            ->whereIn('id', $threadUserIds)
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->withCount(['chatMessages as unread_count' => function ($query) {
                $query->where('is_from_admin', false)->whereNull('read_by_admin_at');
            }])
            ->get()
            ->map(function ($user) {
                $last = $user->chatMessages()->latest()->first();
                $user->last_message = $last;

                return $user;
            })
            ->sortByDesc(fn ($u) => $u->last_message?->created_at)
            ->values();

        $stats = [
            'threads' => $threads->count(),
            'unread' => $threads->sum('unread_count'),
        ];

        return view('admin.communications.chat.index', compact('threads', 'stats', 'search'));
    }

    public function show(User $user)
    {
        $messages = $user->chatMessages()->with('author')->get();

        ChatMessage::where('user_id', $user->id)
            ->where('is_from_admin', false)
            ->whereNull('read_by_admin_at')
            ->update(['read_by_admin_at' => now()]);

        return view('admin.communications.chat.show', compact('user', 'messages'));
    }

    public function reply(Request $request, User $user)
    {
        $request->validate(['content' => ['required', 'string', 'max:2000']]);

        ChatMessage::create([
            'user_id' => $user->id,
            'author_id' => auth()->id(),
            'is_from_admin' => true,
            'content' => $request->input('content'),
            'read_by_admin_at' => now(),
        ]);

        return redirect()->route('admin.communications.chat.show', $user);
    }

    public function updateMessage(Request $request, ChatMessage $message)
    {
        $request->validate(['content' => ['required', 'string', 'max:2000']]);

        $message->update([
            'content' => $request->input('content'),
            'edited_at' => now(),
        ]);

        return redirect()->route('admin.communications.chat.show', $message->user_id);
    }

    public function destroyMessage(ChatMessage $message)
    {
        $userId = $message->user_id;
        $message->delete();

        return redirect()->route('admin.communications.chat.show', $userId);
    }

    public function toggleAccess(User $user)
    {
        $user->update(['chat_enabled' => ! $user->chat_enabled]);

        return redirect()
            ->back()
            ->with('success', $user->chat_enabled ? "Écriture réactivée pour {$user->name}." : "Écriture bloquée pour {$user->name}.");
    }
}
