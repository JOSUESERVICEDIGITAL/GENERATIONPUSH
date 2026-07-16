<x-layouts.admin title="Formations">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($formations->pluck('id')),
            openEdit(formation) {
                this.editing = formation;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Formations</h1>
                <p class="text-muted-foreground mt-2">Gère les formations et programmes de leadership</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="plus" class="w-4 h-4" />
                Créer une formation
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">En cours</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total participants</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['participants'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Revenu estimé</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['revenue'], 0, ',', ' ') }} $</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des formations</h2>
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
                            placeholder="Rechercher une formation..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="active" @selected($status === 'active')>En cours</option>
                        <option value="draft" @selected($status === 'draft')>Brouillon</option>
                        <option value="completed" @selected($status === 'completed')>Terminé</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.programs.formations.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="formation(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} formation(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.programs.formations.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Nom du programme</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Formateur</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Durée</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Participants</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Prix</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($formations as $formation)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $formation->id }}"
                                                @change="$event.target.checked ? selected.push({{ $formation->id }}) : selected = selected.filter(i => i !== {{ $formation->id }})"
                                                :checked="selected.includes({{ $formation->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4 font-medium text-foreground max-w-xs">{{ $formation->name }}</td>
                                        <td class="px-6 py-4">{{ $formation->trainer ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <x-icon name="calendar" class="w-4 h-4 text-muted-foreground" />
                                                <span>{{ $formation->duration ?? '—' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <x-icon name="users" class="w-4 h-4 text-muted-foreground" />
                                                <span class="font-medium">{{ $formation->participants }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <x-icon name="dollar-sign" class="w-4 h-4 text-accent" />
                                                <span class="font-semibold">{{ number_format($formation->price, 0, ',', ' ') }} $</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><x-formation-status-badge :status="$formation->status" /></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $formation->id }}, name: @js($formation->name), trainer: @js($formation->trainer), duration: @js($formation->duration), participants: {{ $formation->participants }}, price: {{ $formation->price }}, status: @js($formation->status) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.programs.formations.destroy', $formation) }}" onsubmit="return confirm('Supprimer cette formation ?');">
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
                                        <td colspan="8" class="px-6 py-8 text-center text-muted-foreground">Aucune formation trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $formations->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Créer une formation">
            <form method="POST" action="{{ route('admin.programs.formations.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_name" value="Nom du programme" />
                    <x-text-input id="create_name" name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_trainer" value="Formateur" />
                    <x-text-input id="create_trainer" name="trainer" type="text" class="mt-1 block w-full" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_duration" value="Durée" />
                        <x-text-input id="create_duration" name="duration" type="text" class="mt-1 block w-full" placeholder="ex: 8 semaines" />
                    </div>
                    <div>
                        <x-input-label for="create_participants" value="Participants" />
                        <x-text-input id="create_participants" name="participants" type="number" min="0" class="mt-1 block w-full" value="0" />
                    </div>
                    <div>
                        <x-input-label for="create_price" value="Prix ($)" />
                        <x-text-input id="create_price" name="price" type="number" min="0" step="0.01" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="active">En cours</option>
                            <option value="completed">Terminé</option>
                        </select>
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
        <x-modal open="editOpen" title="Modifier la formation">
            <form method="POST" :action="`{{ url('admin/programs/formations') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="edit_name" value="Nom du programme" />
                    <input id="edit_name" name="name" type="text" x-model="editing.name" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_trainer" value="Formateur" />
                    <input id="edit_trainer" name="trainer" type="text" x-model="editing.trainer" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_duration" value="Durée" />
                        <input id="edit_duration" name="duration" type="text" x-model="editing.duration" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_participants" value="Participants" />
                        <input id="edit_participants" name="participants" type="number" min="0" x-model="editing.participants" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_price" value="Prix ($)" />
                        <input id="edit_price" name="price" type="number" min="0" step="0.01" x-model="editing.price" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                    </div>
                    <div>
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="active">En cours</option>
                            <option value="completed">Terminé</option>
                        </select>
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
