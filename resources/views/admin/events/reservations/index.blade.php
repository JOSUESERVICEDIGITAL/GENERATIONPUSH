<x-layouts.admin title="Réservations">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($reservations->pluck('id')),
            eventsByType: {
                conference: @js($conferences),
                masterclass: @js($masterclasses),
                coaching_session: @js($coachingSessions),
            },
            createType: 'conference',
            createEventId: '',
            openEdit(reservation) {
                this.editing = reservation;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Réservations</h1>
                <p class="text-muted-foreground mt-2">Suis les inscriptions des membres à tes événements</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="clipboard-check" class="w-4 h-4" />
                Ajouter une réservation
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
                <p class="text-xs text-muted-foreground mb-2">Confirmées</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">En attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Annulées</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des réservations</h2>
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
                            placeholder="Rechercher par utilisateur..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="type" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les événements</option>
                        <option value="conference" @selected($type === 'conference')>Conférence</option>
                        <option value="masterclass" @selected($type === 'masterclass')>Masterclass</option>
                        <option value="coaching_session" @selected($type === 'coaching_session')>Coaching</option>
                    </select>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="confirmed" @selected($status === 'confirmed')>Confirmée</option>
                        <option value="pending" @selected($status === 'pending')>En attente</option>
                        <option value="cancelled" @selected($status === 'cancelled')>Annulée</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status || $type)
                        <a href="{{ route('admin.events.reservations.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="réservation(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} réservation(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.events.reservations.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Utilisateur</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Événement</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Type</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reservations as $reservation)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $reservation->id }}"
                                                @change="$event.target.checked ? selected.push({{ $reservation->id }}) : selected = selected.filter(i => i !== {{ $reservation->id }})"
                                                :checked="selected.includes({{ $reservation->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-foreground">{{ $reservation->user->name ?? '—' }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $reservation->user->email ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $reservation->reservable->title ?? 'Événement supprimé' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-md text-xs font-medium bg-secondary text-foreground">
                                                @switch($reservation->reservable_type)
                                                    @case('conference') Conférence @break
                                                    @case('masterclass') Masterclass @break
                                                    @case('coaching_session') Coaching @break
                                                    @default {{ $reservation->reservable_type }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ match($reservation->status) { 'confirmed' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400', 'pending' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-400', default => 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400' } }}">
                                                {{ $reservation->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $reservation->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $reservation->id }}, user_id: {{ $reservation->user_id }}, reservable_type: @js($reservation->reservable_type), reservable_id: {{ $reservation->reservable_id }}, status: @js($reservation->status) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.events.reservations.destroy', $reservation) }}" onsubmit="return confirm('Supprimer cette réservation ?');">
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
                                        <td colspan="7" class="px-6 py-8 text-center text-muted-foreground">Aucune réservation trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $reservations->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter une réservation">
            <form method="POST" action="{{ route('admin.events.reservations.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_user_id" value="Utilisateur" />
                    <select id="create_user_id" name="user_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                        <option value="">— Choisir —</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="create_reservable_type" value="Type d'événement" />
                    <select id="create_reservable_type" name="reservable_type" x-model="createType" @change="createEventId = ''" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="conference">Conférence</option>
                        <option value="masterclass">Masterclass</option>
                        <option value="coaching_session">Coaching</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="create_reservable_id" value="Événement" />
                    <select id="create_reservable_id" name="reservable_id" x-model="createEventId" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                        <option value="">— Choisir —</option>
                        <template x-for="event in eventsByType[createType]" :key="event.id">
                            <option :value="event.id" x-text="event.title"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <x-input-label for="create_status" value="Statut" />
                    <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmée</option>
                        <option value="cancelled">Annulée</option>
                    </select>
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
        <x-modal open="editOpen" title="Modifier la réservation">
            <form method="POST" :action="`{{ url('admin/events/reservations') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="edit_user_id" value="Utilisateur" />
                    <select id="edit_user_id" name="user_id" x-model="editing.user_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="edit_reservable_type" value="Type d'événement" />
                    <select id="edit_reservable_type" name="reservable_type" x-model="editing.reservable_type" @change="editing.reservable_id = ''" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="conference">Conférence</option>
                        <option value="masterclass">Masterclass</option>
                        <option value="coaching_session">Coaching</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="edit_reservable_id" value="Événement" />
                    <select id="edit_reservable_id" name="reservable_id" x-model="editing.reservable_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                        <template x-for="event in eventsByType[editing.reservable_type]" :key="event.id">
                            <option :value="event.id" x-text="event.title"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <x-input-label for="edit_status" value="Statut" />
                    <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmée</option>
                        <option value="cancelled">Annulée</option>
                    </select>
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
