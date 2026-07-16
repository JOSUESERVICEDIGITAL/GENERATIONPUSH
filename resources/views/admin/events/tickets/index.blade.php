<x-layouts.admin title="Billetterie">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($tickets->pluck('id')),
            eventsByType: {
                conference: @js($conferences),
                masterclass: @js($masterclasses),
                coaching_session: @js($coachingSessions),
            },
            createType: 'conference',
            createEventId: '',
            openEdit(ticket) {
                this.editing = ticket;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Billetterie</h1>
                <p class="text-muted-foreground mt-2">Gère les types de billets vendus pour tes événements</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="ticket" class="w-4 h-4" />
                Créer un billet
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
                <p class="text-xs text-muted-foreground mb-2">Types de billets</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Actifs</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Billets vendus</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['sold'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Revenu billetterie</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['revenue'] ?? 0, 2) }} $</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des billets</h2>
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
                            placeholder="Rechercher un type de billet..."
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
                        <option value="active" @selected($status === 'active')>Actif</option>
                        <option value="sold_out" @selected($status === 'sold_out')>Épuisé</option>
                        <option value="closed" @selected($status === 'closed')>Clôturé</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status || $type)
                        <a href="{{ route('admin.events.tickets.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="billet(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} billet(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.events.tickets.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Billet</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Événement</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Prix</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Vendus / Total</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $ticket)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $ticket->id }}"
                                                @change="$event.target.checked ? selected.push({{ $ticket->id }}) : selected = selected.filter(i => i !== {{ $ticket->id }})"
                                                :checked="selected.includes({{ $ticket->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <x-icon name="ticket" class="w-4 h-4 text-accent" />
                                                <span class="font-medium text-foreground">{{ $ticket->title }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $ticket->ticketable->title ?? 'Événement supprimé' }}</td>
                                        <td class="px-6 py-4 font-semibold">{{ number_format($ticket->price, 2) }} $</td>
                                        <td class="px-6 py-4">{{ $ticket->quantity_sold }} / {{ $ticket->quantity_total }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ match($ticket->status) { 'active' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400', 'sold_out' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-400', default => 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' } }}">
                                                {{ $ticket->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $ticket->id }}, ticketable_type: @js($ticket->ticketable_type), ticketable_id: {{ $ticket->ticketable_id }}, title: @js($ticket->title), price: {{ $ticket->price }}, quantity_total: {{ $ticket->quantity_total }}, quantity_sold: {{ $ticket->quantity_sold }}, status: @js($ticket->status) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.events.tickets.destroy', $ticket) }}" onsubmit="return confirm('Supprimer ce billet ?');">
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
                                        <td colspan="7" class="px-6 py-8 text-center text-muted-foreground">Aucun billet trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Créer un billet">
            <form method="POST" action="{{ route('admin.events.tickets.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_title" value="Nom du billet" />
                    <x-text-input id="create_title" name="title" type="text" class="mt-1 block w-full" placeholder="ex: VIP, Standard, Early Bird" required />
                </div>
                <div>
                    <x-input-label for="create_ticketable_type" value="Type d'événement" />
                    <select id="create_ticketable_type" name="ticketable_type" x-model="createType" @change="createEventId = ''" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="conference">Conférence</option>
                        <option value="masterclass">Masterclass</option>
                        <option value="coaching_session">Coaching</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="create_ticketable_id" value="Événement" />
                    <select id="create_ticketable_id" name="ticketable_id" x-model="createEventId" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                        <option value="">— Choisir —</option>
                        <template x-for="event in eventsByType[createType]" :key="event.id">
                            <option :value="event.id" x-text="event.title"></option>
                        </template>
                    </select>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="create_price" value="Prix ($)" />
                        <x-text-input id="create_price" name="price" type="number" min="0" step="0.01" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="create_quantity_total" value="Quantité totale" />
                        <x-text-input id="create_quantity_total" name="quantity_total" type="number" min="0" class="mt-1 block w-full" value="0" />
                    </div>
                    <div>
                        <x-input-label for="create_quantity_sold" value="Déjà vendus" />
                        <x-text-input id="create_quantity_sold" name="quantity_sold" type="number" min="0" class="mt-1 block w-full" value="0" />
                    </div>
                </div>
                <div>
                    <x-input-label for="create_status" value="Statut" />
                    <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="active">Actif</option>
                        <option value="sold_out">Épuisé</option>
                        <option value="closed">Clôturé</option>
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
        <x-modal open="editOpen" title="Modifier le billet">
            <form method="POST" :action="`{{ url('admin/events/tickets') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="edit_title" value="Nom du billet" />
                    <input id="edit_title" name="title" type="text" x-model="editing.title" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_ticketable_type" value="Type d'événement" />
                    <select id="edit_ticketable_type" name="ticketable_type" x-model="editing.ticketable_type" @change="editing.ticketable_id = ''" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="conference">Conférence</option>
                        <option value="masterclass">Masterclass</option>
                        <option value="coaching_session">Coaching</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="edit_ticketable_id" value="Événement" />
                    <select id="edit_ticketable_id" name="ticketable_id" x-model="editing.ticketable_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                        <template x-for="event in eventsByType[editing.ticketable_type]" :key="event.id">
                            <option :value="event.id" x-text="event.title"></option>
                        </template>
                    </select>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="edit_price" value="Prix ($)" />
                        <input id="edit_price" name="price" type="number" min="0" step="0.01" x-model="editing.price" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                    </div>
                    <div>
                        <x-input-label for="edit_quantity_total" value="Quantité totale" />
                        <input id="edit_quantity_total" name="quantity_total" type="number" min="0" x-model="editing.quantity_total" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_quantity_sold" value="Déjà vendus" />
                        <input id="edit_quantity_sold" name="quantity_sold" type="number" min="0" x-model="editing.quantity_sold" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                </div>
                <div>
                    <x-input-label for="edit_status" value="Statut" />
                    <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="active">Actif</option>
                        <option value="sold_out">Épuisé</option>
                        <option value="closed">Clôturé</option>
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
