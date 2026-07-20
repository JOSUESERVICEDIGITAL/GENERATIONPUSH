<x-layouts.admin title="Messages de contact">
    <div
        x-data="{
            viewOpen: false,
            viewing: {},
            openView(m) {
                this.viewing = m;
                this.viewOpen = true;
            },
        }"
        class="space-y-6"
    >
        <div>
            <h1 class="text-3xl font-bold text-foreground">Messages de contact</h1>
            <p class="text-muted-foreground mt-2">Messages reçus depuis le formulaire de contact du site public</p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Nouveaux</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['new'] }}</p>
            </div>
        </div>

        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Boîte de réception</h2>
            </div>
            <div class="p-6 space-y-4">
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[220px] max-w-sm">
                        <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                        <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200">
                    </div>
                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="new" @selected($status === 'new')>Nouveau</option>
                        <option value="read" @selected($status === 'read')>Lu</option>
                        <option value="replied" @selected($status === 'replied')>Répondu</option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">Filtrer</button>
                    @if ($search || $status)
                        <a href="{{ route('admin.communications.messages.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <div class="border border-border rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-secondary border-b border-border">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">De</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Sujet</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($messages as $message)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200 {{ $message->status === 'new' ? 'font-medium' : '' }}">
                                        <td class="px-6 py-4">
                                            <div class="text-foreground">{{ $message->name }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $message->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 max-w-xs truncate">{{ $message->subject ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ match($message->status) { 'new' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400', 'replied' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400', default => 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' } }}">
                                                {{ $message->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-muted-foreground">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="openView({ name: @js($message->name), email: @js($message->email), subject: @js($message->subject), message: @js($message->message), date: @js($message->created_at->format('d/m/Y H:i')) })" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                                    <x-icon name="eye" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                @if ($message->status === 'new')
                                                    <form method="POST" action="{{ route('admin.communications.messages.read', $message) }}">
                                                        @csrf
                                                        <button type="submit" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200" title="Marquer comme lu">
                                                            <x-icon name="check" class="w-4 h-4 text-muted-foreground hover:text-green-600" />
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('admin.communications.messages.destroy', $message) }}" onsubmit="return confirm('Supprimer ce message ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                                        <x-icon name="trash" class="w-4 h-4 text-muted-foreground hover:text-destructive" />
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-8 text-center text-muted-foreground">Aucun message reçu pour l'instant</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>{{ $messages->links() }}</div>
            </div>
        </div>

        <x-modal open="viewOpen" title="Message reçu">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-border"><span class="text-muted-foreground">De</span><span class="font-medium text-foreground" x-text="viewing.name"></span></div>
                <div class="flex justify-between py-2 border-b border-border"><span class="text-muted-foreground">Email</span><span class="font-medium text-foreground" x-text="viewing.email"></span></div>
                <div class="flex justify-between py-2 border-b border-border"><span class="text-muted-foreground">Sujet</span><span class="font-medium text-foreground" x-text="viewing.subject || '—'"></span></div>
                <div class="py-2 border-b border-border"><span class="text-muted-foreground block mb-1">Message</span><p class="text-foreground whitespace-pre-line" x-text="viewing.message"></p></div>
                <div class="flex justify-between py-2"><span class="text-muted-foreground">Reçu le</span><span class="font-medium text-foreground" x-text="viewing.date"></span></div>
            </div>
            <div class="pt-4">
                <button type="button" @click="viewOpen = false" class="w-full px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">Fermer</button>
            </div>
        </x-modal>
    </div>
</x-layouts.admin>
