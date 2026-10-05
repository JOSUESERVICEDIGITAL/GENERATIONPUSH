<?php

namespace App\Http\Controllers\Admin\Communications;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Afficher le chat communautaire.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $messages = ChatMessage::query()
            ->with('author')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('content', 'like', "%{$search}%")
                        ->orWhereHas('author', function ($authorQuery) use ($search) {
                            $authorQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(50)
            ->withQueryString();

        $unreadCount = ChatMessage::query()
            ->where('is_from_admin', false)
            ->whereDoesntHave('reads', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->count();

        $stats = [
            'messages' => ChatMessage::count(),

            'member_messages' => ChatMessage::query()
                ->where('is_from_admin', false)
                ->count(),

            'admin_messages' => ChatMessage::query()
                ->where('is_from_admin', true)
                ->count(),

            'unread' => $unreadCount,
        ];

        return view('admin.communications.chat.index', [
            'messages' => $messages,
            'stats' => $stats,
            'search' => $search,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Envoyer un message depuis l'administration.
     */
    public function reply(Request $request): RedirectResponse
    {
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

        ChatMessage::create([
            'author_id' => auth()->id(),
            'is_from_admin' => true,
            'content' => trim($validated['content']),
        ]);

        return redirect()
            ->route('admin.communications.chat.index')
            ->with('success', 'Votre message a été envoyé.');
    }

    /**
     * Marquer un message comme lu par l'administrateur connecté.
     */
    public function markAsRead(ChatMessage $message): RedirectResponse
    {
        $admin = auth()->user();

        /*
         * Un administrateur n'a besoin de marquer
         * comme lus que les messages provenant des membres.
         */
        if (! $message->is_from_admin) {
            $message->markAsReadBy($admin);
        }

        return back();
    }

    /**
     * Marquer tous les messages des membres comme lus.
     */
    public function markAllAsRead(): RedirectResponse
    {
        $admin = auth()->user();

        $messages = ChatMessage::query()
            ->where('is_from_admin', false)
            ->whereDoesntHave('reads', function ($query) use ($admin) {
                $query->where('user_id', $admin->id);
            })
            ->get();

        foreach ($messages as $message) {
            $message->markAsReadBy($admin);
        }

        return back()->with(
            'success',
            'Tous les nouveaux messages ont été marqués comme lus.'
        );
    }

    /**
     * Modifier un message envoyé par l'administrateur connecté.
     */
    public function updateMessage(
        Request $request,
        ChatMessage $message
    ): RedirectResponse {
        $admin = auth()->user();

        /*
         * Un administrateur ne peut modifier
         * que ses propres messages.
         */
        if (
            ! $message->is_from_admin ||
            $message->author_id !== $admin->id
        ) {
            abort(403);
        }

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

        $message->update([
            'content' => trim($validated['content']),
            'edited_at' => now(),
        ]);

        return back()->with(
            'success',
            'Votre message a été modifié.'
        );
    }

    /**
     * Supprimer un message envoyé par l'administrateur connecté.
     */
    public function destroyMessage(
        ChatMessage $message
    ): RedirectResponse {
        $admin = auth()->user();

        /*
         * Un administrateur ne peut supprimer
         * que ses propres messages.
         */
        if (
            ! $message->is_from_admin ||
            $message->author_id !== $admin->id
        ) {
            abort(403);
        }

        $message->delete();

        return back()->with(
            'success',
            'Votre message a été supprimé.'
        );
    }

    /**
     * Activer ou désactiver l'écriture dans le chat
     * pour un membre.
     *
     * Cette méthode peut être utilisée depuis la gestion
     * des membres. Elle n'est plus appelée par les routes
     * du chat communautaire.
     */
    public function toggleAccess(User $user): RedirectResponse
    {
        $user->update([
            'chat_enabled' => ! $user->chat_enabled,
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                $user->chat_enabled
                    ? "Écriture réactivée pour {$user->name}."
                    : "Écriture bloquée pour {$user->name}."
            );
    }
}
