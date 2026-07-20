<x-layouts.admin title="Abonnés newsletter">
    <div>
        <h1 class="text-3xl font-bold text-foreground">Abonnés newsletter</h1>
        <p class="text-muted-foreground mt-2">Emails inscrits depuis le formulaire du site public</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm mt-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-card border border-border rounded-lg p-4 mt-6 max-w-xs">
        <p class="text-xs text-muted-foreground mb-2">Total abonnés actifs</p>
        <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
    </div>

    <div class="bg-card border border-border rounded-xl overflow-hidden mt-6">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-foreground">Liste des abonnés</h2>
        </div>
        <div class="p-6 space-y-4">
            <form method="GET" class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                    <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher un email..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200">
                </div>
                @if ($search)
                    <a href="{{ route('admin.communications.subscribers.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                @endif
            </form>

            <div class="border border-border rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-secondary border-b border-border">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-foreground">Email</th>
                            <th class="px-6 py-3 text-left font-semibold text-foreground">Inscrit le</th>
                            <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                            <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscribers as $subscriber)
                            <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                <td class="px-6 py-4 text-foreground">{{ $subscriber->email }}</td>
                                <td class="px-6 py-4 text-xs text-muted-foreground">{{ $subscriber->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $subscriber->status === 'subscribed' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' }}">
                                        {{ $subscriber->status === 'subscribed' ? 'Abonné' : 'Désabonné' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.communications.subscribers.destroy', $subscriber) }}" onsubmit="return confirm('Supprimer cet abonné ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                            <x-icon name="trash" class="w-4 h-4 text-muted-foreground hover:text-destructive" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-muted-foreground">Aucun abonné pour l'instant</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>{{ $subscribers->links() }}</div>
        </div>
    </div>
</x-layouts.admin>
