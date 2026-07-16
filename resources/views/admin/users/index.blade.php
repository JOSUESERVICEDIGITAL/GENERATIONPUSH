<x-layouts.admin title="Utilisateurs">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Gestion des utilisateurs</h1>
            <p class="text-muted-foreground mt-2">Gérez les utilisateurs de la plateforme Generation PUSH</p>
        </div>
        <a
            href="{{ route('admin.users.create') }}"
            class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
        >
            + Ajouter un utilisateur
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-card border border-border rounded-lg p-4">
            <p class="text-sm text-muted-foreground mb-1">Total</p>
            <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-card border border-border rounded-lg p-4">
            <p class="text-sm text-muted-foreground mb-1">Actifs</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-card border border-border rounded-lg p-4">
            <p class="text-sm text-muted-foreground mb-1">Suspendus</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['suspended'] }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-foreground">Liste des utilisateurs</h2>
        </div>

        <div class="p-6 space-y-4">
            <!-- Recherche -->
            <form method="GET" class="flex items-center gap-2">
                <div class="relative flex-1 max-w-sm">
                    <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher par nom ou email..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                    >
                </div>
                @if ($search)
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                @endif
            </form>

            <!-- Tableau -->
            <div class="border border-border rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-secondary border-b border-border">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Nom</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Email</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Téléphone</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Pays</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Ville</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Inscription</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Rôle</th>
                                <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                    <td class="px-6 py-4 font-medium text-foreground">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-muted-foreground">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ $user->phone ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $user->country ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $user->city ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4"><x-status-badge :status="$user->status" /></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <x-icon name="alert-triangle" class="w-4 h-4 text-muted-foreground hidden" />
                                            <span>{{ $user->role }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                                <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                            </a>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?');">
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
                                <tr>
                                    <td colspan="9" class="px-6 py-8 text-center text-muted-foreground">Aucune donnée trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
