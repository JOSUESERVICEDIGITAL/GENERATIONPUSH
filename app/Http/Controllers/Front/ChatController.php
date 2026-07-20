<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreChatMessageRequest;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $messages = $user->chatMessages()->with('author')->get();

        // Marque les messages admin comme lus par le membre à l'ouverture du thread.
        ChatMessage::where('user_id', $user->id)
            ->where('is_from_admin', true)
            ->whereNull('read_by_member_at')
            ->update(['read_by_member_at' => now()]);

        return view('front.chat', compact('messages', 'user'));
    }

    public function store(StoreChatMessageRequest $request)
    {
        $user = auth()->user();

        ChatMessage::create([
            'user_id' => $user->id,
            'author_id' => $user->id,
            'is_from_admin' => false,
            'content' => $request->validated('content'),
        ]);

        return redirect()->route('front.chat.index');
    }

    public function update(Request $request, ChatMessage $message)
    {
        abort_unless($message->author_id === auth()->id() && ! $message->is_from_admin, 403);

        $request->validate(['content' => ['required', 'string', 'max:2000']]);

        $message->update([
            'content' => $request->input('content'),
            'edited_at' => now(),
        ]);

        return redirect()->route('front.chat.index');
    }

    public function destroy(ChatMessage $message)
    {
        abort_unless($message->author_id === auth()->id() && ! $message->is_from_admin, 403);

        $message->delete();

        return redirect()->route('front.chat.index');
    }
}
