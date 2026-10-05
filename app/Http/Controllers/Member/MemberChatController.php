<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberChatController extends Controller
{
    /**
     * Afficher le chat communautaire.
     */
    public function index(): View
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Vérification de l'accès au chat
        |--------------------------------------------------------------------------
        */

        if (! $user->chat_enabled) {
            abort(403, 'Le chat communautaire est actuellement désactivé pour votre compte.');
        }

        /*
        |--------------------------------------------------------------------------
        | Messages
        |--------------------------------------------------------------------------
        |
        | On récupère les messages du plus ancien au plus récent afin
        | d'afficher une vraie conversation.
        |
        */

        $messages = ChatMessage::query()
            ->with('author')
            ->latest()
            ->paginate(30);

        /*
        |--------------------------------------------------------------------------
        | Marquer les messages visibles comme lus
        |--------------------------------------------------------------------------
        */

        foreach ($messages->getCollection() as $message) {
            if ($message->author_id !== $user->id) {
                $message->markAsReadBy($user);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Nombre de messages non lus
        |--------------------------------------------------------------------------
        */

        $unreadMessagesCount = ChatMessage::query()
            ->where('author_id', '!=', $user->id)
            ->whereDoesntHave('reads', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();

        return view('member.chat.index', [
            'messages' => $messages,
            'unreadMessagesCount' => $unreadMessagesCount,
        ]);
    }

    /**
     * Envoyer un message dans le chat communautaire.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Vérification de l'accès
        |--------------------------------------------------------------------------
        */

        if (! $user->chat_enabled) {
            return back()->with('error', 'Le chat communautaire est actuellement désactivé pour votre compte.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'content' => [
                'required',
                'string',
                'min:1',
                'max:5000',
            ],
        ], [
            'content.required' => 'Veuillez saisir un message.',
            'content.max' => 'Votre message ne peut pas dépasser 5000 caractères.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Création du message
        |--------------------------------------------------------------------------
        */

        ChatMessage::create([
            'author_id' => $user->id,
            'is_from_admin' => false,
            'content' => trim($validated['content']),
        ]);

        return back()->with('success', 'Votre message a été envoyé.');
    }

    /**
     * Marquer un message comme lu.
     */
    public function markAsRead(ChatMessage $message): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->chat_enabled) {
            return back();
        }

        /*
        | L'auteur n'a pas besoin de marquer son propre message comme lu.
        */

        if ($message->author_id !== $user->id) {
            $message->markAsReadBy($user);
        }

        return back();
    }
}
