<x-layouts.admin title="Conférences">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($conferences->pluck('id')),
            openEdit(conference) {
                this.editing = conference;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Conférences</h1>
                <p class="text-muted-foreground mt-2">Gère les conférences et événements principaux</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="zap" class="w-4 h-4" />
                Créer une conférence
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

        <!-- Featured Events -->
        @if ($featured->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($featured as $event)
                    <div class="bg-card border border-border rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300">
                        <div class="aspect-video bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center">
                            <x-icon name="zap" class="w-12 h-12 text-accent/50" />
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="font-semibold text-foreground text-sm line-clamp-2">{{ $event->title }}</h3>
                                <x-conference-status-badge :status="$event->status" class="shrink-0 !text-[10px] !px-2 !py-0.5" />
                            </div>
                            <div class="space-y-2 text-xs text-muted-foreground">
                                <div class="flex items-center gap-2">
                                    <x-icon name="calendar" class="w-3 h-3" />
                                    <span>{{ $event->country }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-icon name="calendar" class="w-3 h-3" />
                                    <span>{{ $event->date?->translatedFormat('d F Y') ?? '—' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-icon name="users" class="w-3 h-3" />
                                    <span>{{ $event->registered }}/{{ $event->capacity }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Toutes les conférences</h2>
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
                            placeholder="Rechercher une conférence..."
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
                        <a href="{{ route('admin.events.conferences.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="conférence(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} conférence(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.events.conferences.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Lieu</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Inscriptions</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($conferences as $conference)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $conference->id }}"
                                                @change="$event.target.checked ? selected.push({{ $conference->id }}) : selected = selected.filter(i => i !== {{ $conference->id }})"
                                                :checked="selected.includes({{ $conference->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-foreground">{{ $conference->title }}</p>
                                            <p class="text-xs text-muted-foreground mt-1">{{ $conference->speaker }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-start gap-2">
                                                <x-icon name="calendar" class="w-4 h-4 text-muted-foreground mt-0.5" />
                                                <div>
                                                    <p class="text-sm font-medium text-foreground">{{ $conference->location ?? '—' }}</p>
                                                    <p class="text-xs text-muted-foreground">{{ $conference->country }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">{{ $conference->date?->translatedFormat('d F Y') ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <x-icon name="users" class="w-4 h-4 text-muted-foreground" />
                                                <div class="flex-1 min-w-[80px]">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-sm font-medium">{{ $conference->registered }}/{{ $conference->capacity }}</span>
                                                        <span class="text-xs text-muted-foreground">{{ $conference->attendancePercentage() }}%</span>
                                                    </div>
                                                    <div class="w-16 h-2 rounded-full bg-secondary overflow-hidden">
                                                        <div class="h-full bg-accent" style="width: {{ $conference->attendancePercentage() }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><x-conference-status-badge :status="$conference->status" /></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $conference->id }}, title: @js($conference->title), speaker: @js($conference->speaker), location: @js($conference->location), country: @js($conference->country), date: @js($conference->date?->format('Y-m-d')), capacity: {{ $conference->capacity }}, registered: {{ $conference->registered }}, status: @js($conference->status) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.events.conferences.destroy', $conference) }}" onsubmit="return confirm('Supprimer cette conférence ?');">
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
                                        <td colspan="7" class="px-6 py-8 text-center text-muted-foreground">Aucune conférence trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $conferences->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Créer une conférence">
            <form method="POST" action="{{ route('admin.events.conferences.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_title" value="Titre" />
                    <x-text-input id="create_title" name="title" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_speaker" value="Intervenant" />
                    <x-text-input id="create_speaker" name="speaker" type="text" class="mt-1 block w-full" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_location" value="Lieu" />
                        <x-text-input id="create_location" name="location" type="text" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="create_country" value="Pays" />
                        <x-text-input id="create_country" name="country" type="text" class="mt-1 block w-full" />
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
        <x-modal open="editOpen" title="Modifier la conférence">
            <form method="POST" :action="`{{ url('admin/events/conferences') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="edit_title" value="Titre" />
                    <input id="edit_title" name="title" type="text" x-model="editing.title" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_speaker" value="Intervenant" />
                    <input id="edit_speaker" name="speaker" type="text" x-model="editing.speaker" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_location" value="Lieu" />
                        <input id="edit_location" name="location" type="text" x-model="editing.location" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_country" value="Pays" />
                        <input id="edit_country" name="country" type="text" x-model="editing.country" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
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
