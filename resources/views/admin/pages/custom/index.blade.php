<x-layouts.admin title="Pages personnalisées">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Pages personnalisées</h1>
            <p class="text-muted-foreground mt-2">FAQ, Conditions d'utilisation, Confidentialité, et toute autre page statique du site public</p>
        </div>
        <a href="{{ route('admin.pages.custom.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
            <x-icon name="plus" class="w-4 h-4" />
            Nouvelle page
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm mt-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-card border border-border rounded-xl overflow-hidden mt-6">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-foreground">Toutes les pages</h2>
        </div>
        <div class="p-6 space-y-4">
            <form method="GET" class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                    <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher une page..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200">
                </div>
                @if ($search)
                    <a href="{{ route('admin.pages.custom.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                @endif
            </form>

            <div class="border border-border rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-secondary border-b border-border">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Titre</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">URL publique</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pages as $page)
                                <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                    <td class="px-6 py-4 font-medium text-foreground">{{ $page->title }}</td>
                                    <td class="px-6 py-4 text-xs text-muted-foreground font-mono">/page/{{ $page->slug }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $page->status === 'published' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' }}">
                                            {{ $page->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.pages.custom.edit', $page) }}" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                                <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                            </a>
                                            <form method="POST" action="{{ route('admin.pages.custom.destroy', $page) }}" onsubmit="return confirm('Supprimer cette page ?');">
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
                                <tr><td colspan="4" class="px-6 py-8 text-center text-muted-foreground">Aucune page trouvée</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>{{ $pages->links() }}</div>
        </div>
    </div>
</x-layouts.admin>
