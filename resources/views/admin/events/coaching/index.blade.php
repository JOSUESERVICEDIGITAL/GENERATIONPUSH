<x-layouts.admin title="Coaching">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($sessions->pluck('id')),
            openEdit(session) {
                this.editing = session;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Coaching</h1>
                <p class="text-muted-foreground mt-2">Gère les séances de coaching individuelles et collectives</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="zap" class="w-4 h-4" />
                Créer une séance
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
                <p class="text-xs text-muted-foreground mb-2">À venir</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['upcoming'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total inscriptions</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['registrations'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Capacité moyenne</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['avg_capacity'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Toutes les séances</h2>
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
                            placeholder="Rechercher une séance..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="upcoming" @selected($status === 'upcoming')>À venir</option>
                        <option value="ongoing" @selected($status === 'ongoing')>En cours</option>
                        <option value="completed" @selected($status === 'completed')>Terminée</option>
                        <option value="cancelled" @selected($status === 'cancelled')>Annulée</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.events.coaching.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="séance(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} séance(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.events.coaching.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Titre</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Coach</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Durée</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Inscriptions</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sessions as $session)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $session->id }}"
                                                @change="$event.target.checked ? selected.push({{ $session->id }}) : selected = selected.filter(i => i !== {{ $session->id }})"
                                                :checked="selected.includes({{ $session->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-foreground">{{ $session->title }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $session->coach ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $session->duration ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $session->date?->translatedFormat('d F Y') ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <x-icon name="users" class="w-4 h-4 text-muted-foreground" />
                                                <div class="flex-1 min-w-[80px]">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-sm font-medium">{{ $session->registered }}/{{ $session->capacity }}</span>
                                                        <span class="text-xs text-muted-foreground">{{ $session->attendancePercentage() }}%</span>
                                                    </div>
                                                    <div class="w-16 h-2 rounded-full bg-secondary overflow-hidden">
                                                        <div class="h-full bg-accent" style="width: {{ $session->attendancePercentage() }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><x-event-status-badge :status="$session->status" /></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $session->id }}, title: @js($session->title), coach: @js($session->coach), duration: @js($session->duration), date: @js($session->date?->format('Y-m-d')), capacity: {{ $session->capacity }}, registered: {{ $session->registered }}, status: @js($session->status) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.events.coaching.destroy', $session) }}" onsubmit="return confirm('Supprimer cette séance ?');">
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
                                        <td colspan="7" class="px-6 py-8 text-center text-muted-foreground">Aucune séance trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $sessions->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Créer une séance">
            <form method="POST" action="{{ route('admin.events.coaching.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_title" value="Titre" />
                    <x-text-input id="create_title" name="title" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_coach" value="Coach" />
                    <x-text-input id="create_coach" name="coach" type="text" class="mt-1 block w-full" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_duration" value="Durée" />
                        <x-text-input id="create_duration" name="duration" type="text" class="mt-1 block w-full" placeholder="ex: 1h30" />
                    </div>
                    <div>
                        <x-input-label for="create_date" value="Date" />
                        <x-text-input id="create_date" name="date" type="date" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="upcoming">À venir</option>
                            <option value="ongoing">En cours</option>
                            <option value="completed">Terminée</option>
                            <option value="cancelled">Annulée</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="create_capacity" value="Capacité" />
                        <x-text-input id="create_capacity" name="capacity" type="number" min="0" class="mt-1 block w-full" value="0" />
                    </div>
                    <div>
                        <x-input-label for="create_registered" value="Inscrits" />
                        <x-text-input id="create_registered" name="registered" type="number" min="0" class="mt-1 block w-full" value="0" />
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
        <x-modal open="editOpen" title="Modifier la séance">
            <form method="POST" :action="`{{ url('admin/events/coaching') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="edit_title" value="Titre" />
                    <input id="edit_title" name="title" type="text" x-model="editing.title" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_coach" value="Coach" />
                    <input id="edit_coach" name="coach" type="text" x-model="editing.coach" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_duration" value="Durée" />
                        <input id="edit_duration" name="duration" type="text" x-model="editing.duration" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_date" value="Date" />
                        <input id="edit_date" name="date" type="date" x-model="editing.date" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="upcoming">À venir</option>
                            <option value="ongoing">En cours</option>
                            <option value="completed">Terminée</option>
                            <option value="cancelled">Annulée</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_capacity" value="Capacité" />
                        <input id="edit_capacity" name="capacity" type="number" min="0" x-model="editing.capacity" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_registered" value="Inscrits" />
                        <input id="edit_registered" name="registered" type="number" min="0" x-model="editing.registered" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
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
