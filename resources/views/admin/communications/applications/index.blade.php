<x-layouts.admin title="Candidatures">
    <div
        x-data="{
            viewOpen: false,
            viewing: {},
            openView(a) { this.viewing = a; this.viewOpen = true; },
        }"
        class="space-y-6"
    >
        <div>
            <h1 class="text-3xl font-bold text-foreground">Candidatures</h1>
            <p class="text-muted-foreground mt-2">Demandes reçues depuis "Devenir Partenaire" et "Devenir Bénévole" sur le site public</p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Partenaires</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['partners'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Bénévoles</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['volunteers'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Nouvelles</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['new'] }}</p>
            </div>
        </div>

        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des candidatures</h2>
            </div>
            <div class="p-6 space-y-4">
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[220px] max-w-sm">
                        <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                        <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200">
                    </div>
                    <select name="type" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les types</option>
                        <option value="partner" @selected($type === 'partner')>Partenaire</option>
                        <option value="volunteer" @selected($type === 'volunteer')>Bénévole</option>
                    </select>
                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="new" @selected($status === 'new')>Nouveau</option>
                        <option value="reviewed" @selected($status === 'reviewed')>Examiné</option>
                        <option value="accepted" @selected($status === 'accepted')>Accepté</option>
                        <option value="rejected" @selected($status === 'rejected')>Refusé</option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">Filtrer</button>
                    @if ($search || $type || $status)
                        <a href="{{ route('admin.communications.applications.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <div class="border border-border rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-secondary border-b border-border">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Nom</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Type</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Organisation</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $app)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="text-foreground">{{ $app->name }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $app->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-md text-xs font-semibold {{ $app->type === 'partner' ? 'bg-accent/10 text-accent' : 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400' }}">{{ $app->typeLabel() }}</span>
                                        </td>
                                        <td class="px-6 py-4">{{ $app->organization ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            <form method="POST" action="{{ route('admin.communications.applications.status', $app) }}">
                                                @csrf
                                                <select name="status" onchange="this.form.submit()" class="rounded-md border-border bg-background text-foreground text-xs focus:border-accent focus:ring-accent">
                                                    <option value="new" @selected($app->status === 'new')>Nouveau</option>
                                                    <option value="reviewed" @selected($app->status === 'reviewed')>Examiné</option>
                                                    <option value="accepted" @selected($app->status === 'accepted')>Accepté</option>
                                                    <option value="rejected" @selected($app->status === 'rejected')>Refusé</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-muted-foreground">{{ $app->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="openView({ name: @js($app->name), email: @js($app->email), phone: @js($app->phone), organization: @js($app->organization), message: @js($app->message), type: @js($app->typeLabel()), date: @js($app->created_at->format('d/m/Y H:i')) })" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                                    <x-icon name="eye" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.communications.applications.destroy', $app) }}" onsubmit="return confirm('Supprimer cette candidature ?');">
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
                                    <tr><td colspan="6" class="px-6 py-8 text-center text-muted-foreground">Aucune candidature reçue pour l'instant</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>{{ $applications->links() }}</div>
            </div>
        </div>

        <x-modal open="viewOpen" title="Détail de la candidature">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-border"><span class="text-muted-foreground">Type</span><span class="font-medium text-foreground" x-text="viewing.type"></span></div>
                <div class="flex justify-between py-2 border-b border-border"><span class="text-muted-foreground">Nom</span><span class="font-medium text-foreground" x-text="viewing.name"></span></div>
                <div class="flex justify-between py-2 border-b border-border"><span class="text-muted-foreground">Email</span><span class="font-medium text-foreground" x-text="viewing.email"></span></div>
                <div class="flex justify-between py-2 border-b border-border"><span class="text-muted-foreground">Téléphone</span><span class="font-medium text-foreground" x-text="viewing.phone || '—'"></span></div>
                <div class="flex justify-between py-2 border-b border-border"><span class="text-muted-foreground">Organisation</span><span class="font-medium text-foreground" x-text="viewing.organization || '—'"></span></div>
                <div class="py-2 border-b border-border"><span class="text-muted-foreground block mb-1">Message</span><p class="text-foreground whitespace-pre-line" x-text="viewing.message"></p></div>
                <div class="flex justify-between py-2"><span class="text-muted-foreground">Reçue le</span><span class="font-medium text-foreground" x-text="viewing.date"></span></div>
            </div>
            <div class="pt-4">
                <button type="button" @click="viewOpen = false" class="w-full px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">Fermer</button>
            </div>
        </x-modal>
    </div>
</x-layouts.admin>
