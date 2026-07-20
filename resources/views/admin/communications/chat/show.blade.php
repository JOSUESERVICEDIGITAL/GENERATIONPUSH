<x-layouts.admin title="Chat avec {{ $user->name }}">
    <div x-data="{ editingId: null, editingContent: '' }" class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.communications.chat.index') }}" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                    <x-icon name="chevron-right" class="w-4 h-4 rotate-180" />
                </a>
                <div>
                    <h1 class="text-xl font-bold text-foreground">{{ $user->name }}</h1>
                    <p class="text-sm text-muted-foreground">{{ $user->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.communications.chat.toggle', $user) }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-lg border text-sm font-medium transition-all duration-200 {{ $user->chat_enabled ? 'border-destructive text-destructive hover:bg-destructive/10' : 'border-green-500 text-green-600 hover:bg-green-50' }}">
                    <x-icon :name="$user->chat_enabled ? 'x' : 'check'" class="w-4 h-4" />
                    {{ $user->chat_enabled ? "Bloquer l'écriture" : "Réactiver l'écriture" }}
                </button>
            </form>
        </div>

        <div class="bg-card border border-border rounded-2xl flex flex-col h-[65vh]">
            <div class="flex-1 overflow-y-auto p-6 space-y-4">
                @forelse ($messages as $message)
                    <div class="flex {{ $message->is_from_admin ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%]">
                            <div x-show="editingId !== {{ $message->id }}" class="rounded-2xl px-4 py-2.5 {{ $message->is_from_admin ? 'bg-accent text-white' : 'bg-secondary text-foreground' }}">
                                <p class="text-sm whitespace-pre-line">{{ $message->content }}</p>
                            </div>

                            <form x-show="editingId === {{ $message->id }}" method="POST" action="{{ route('admin.communications.chat.messages.update', $message) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="content" x-model="editingContent" class="flex-1 text-sm px-3 py-2 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent">
                                <button type="submit" class="text-xs font-semibold text-accent cursor-pointer">OK</button>
                                <button type="button" @click="editingId = null" class="text-xs text-muted-foreground cursor-pointer">Annuler</button>
                            </form>

                            <div class="flex items-center gap-2 mt-1 {{ $message->is_from_admin ? 'justify-end' : 'justify-start' }}">
                                <p class="text-[11px] text-muted-foreground">
                                    {{ $message->is_from_admin ? 'Toi (admin)' : $user->name }} · {{ $message->created_at->format('d/m H:i') }}
                                    @if ($message->edited_at) (modifié) @endif
                                </p>
                                <button type="button" @click="editingId = {{ $message->id }}; editingContent = @js($message->content)" class="text-[11px] text-muted-foreground hover:text-accent cursor-pointer">Modifier</button>
                                <form method="POST" action="{{ route('admin.communications.chat.messages.destroy', $message) }}" onsubmit="return confirm('Supprimer ce message ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] text-muted-foreground hover:text-destructive cursor-pointer">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted-foreground text-sm py-12">Aucun message pour l'instant</p>
                @endforelse
            </div>

            <div class="border-t border-border p-4">
                <form method="POST" action="{{ route('admin.communications.chat.reply', $user) }}" class="flex items-center gap-3">
                    @csrf
                    <input type="text" name="content" placeholder="Répondre à {{ $user->name }}..." required class="flex-1 px-4 py-2.5 rounded-full border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                    <button type="submit" class="w-10 h-10 rounded-full bg-accent text-white flex items-center justify-center hover:opacity-90 transition-all duration-200 cursor-pointer shrink-0">
                        <x-icon name="send" class="w-4 h-4" />
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
