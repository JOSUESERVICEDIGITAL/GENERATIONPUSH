<x-layouts.admin title="Leaders">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($leaders->pluck('id')),
            openEdit(member) {
                this.editing = member;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Leaders</h1>
                <p class="text-muted-foreground mt-2">Gère les leaders de la communauté Generation PUSH</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="plus" class="w-4 h-4" />
                Ajouter un leader
            </button>
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

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des leaders</h2>
            </div>

            <div class="p-6 space-y-4">
                <!-- Filtres -->
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[220px] max-w-sm">
                        <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Rechercher par nom ou email..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="active" @selected($status === 'active')>Actif</option>
                        <option value="inactive" @selected($status === 'inactive')>Inactif</option>
                        <option value="suspended" @selected($status === 'suspended')>Suspendu</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.leaders.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="leader(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} leader(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.leaders.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <!-- Tableau -->
                <div class="border border-border rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-secondary border-b border-border">
                                <tr>
                                    <th class="px-4 py-3 w-10">
                                        <input
                                            type="checkbox"
                                            class="rounded border-border"
                                            @change="selected = $event.target.checked ? [...allIds] : []"
                                            :checked="selected.length === allIds.length && allIds.length > 0"
                                        >
                                    </th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Nom</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Email</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Téléphone</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Pays</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Ville</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Inscription</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaders as $leader)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $leader->id }}"
                                                @change="$event.target.checked ? selected.push({{ $leader->id }}) : selected = selected.filter(i => i !== {{ $leader->id }})"
                                                :checked="selected.includes({{ $leader->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4 font-medium text-foreground">{{ $leader->name }}</td>
                                        <td class="px-6 py-4 text-muted-foreground">{{ $leader->email }}</td>
                                        <td class="px-6 py-4">{{ $leader->phone ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $leader->country ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $leader->city ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $leader->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4"><x-status-badge :status="$leader->status" /></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $leader->id }}, name: @js($leader->name), email: @js($leader->email), phone: @js($leader->phone), country: @js($leader->country), city: @js($leader->city), status: @js($leader->status) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.leaders.destroy', $leader) }}" onsubmit="return confirm('Supprimer ce leader ?');">
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
                                        <td colspan="9" class="px-6 py-8 text-center text-muted-foreground">Aucun leader trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $leaders->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter un leader">
            <form method="POST" action="{{ route('admin.leaders.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_name" value="Nom complet" />
                    <x-text-input id="create_name" name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_email" value="Email" />
                    <x-text-input id="create_email" name="email" type="email" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_password" value="Mot de passe" />
                    <x-text-input id="create_password" name="password" type="password" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_password_confirmation" value="Confirmation" />
                    <x-text-input id="create_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_phone" value="Téléphone" />
                        <x-text-input id="create_phone" name="phone" type="text" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                            <option value="suspended">Suspendu</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="create_country" value="Pays" />
                        <x-text-input id="create_country" name="country" type="text" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="create_city" value="Ville" />
                        <x-text-input id="create_city" name="city" type="text" class="mt-1 block w-full" />
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                        Créer
                    </button>
                    <button type="button" @click="createOpen = false" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">
                        Annuler
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Édition -->
        <x-modal open="editOpen" title="Modifier le leader">
            <form method="POST" :action="`{{ url('admin/leaders') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="edit_name" value="Nom complet" />
                    <input id="edit_name" name="name" type="text" x-model="editing.name" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_email" value="Email" />
                    <input id="edit_email" name="email" type="email" x-model="editing.email" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_password" value="Nouveau mot de passe (optionnel)" />
                    <input id="edit_password" name="password" type="password" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div>
                    <x-input-label for="edit_password_confirmation" value="Confirmation" />
                    <input id="edit_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_phone" value="Téléphone" />
                        <input id="edit_phone" name="phone" type="text" x-model="editing.phone" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                            <option value="suspended">Suspendu</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_country" value="Pays" />
                        <input id="edit_country" name="country" type="text" x-model="editing.country" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_city" value="Ville" />
                        <input id="edit_city" name="city" type="text" x-model="editing.city" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                        Enregistrer
                    </button>
                    <button type="button" @click="editOpen = false" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">
                        Annuler
                    </button>
                </div>
            </form>
        </x-modal>
    </div>
</x-layouts.admin>
