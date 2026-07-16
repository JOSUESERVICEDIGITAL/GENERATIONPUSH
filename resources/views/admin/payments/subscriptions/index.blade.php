<x-layouts.admin title="Abonnements">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($subscriptions->pluck('id')),
            openEdit(subscription) {
                this.editing = subscription;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Abonnements</h1>
                <p class="text-muted-foreground mt-2">Suis les abonnements récurrents de tes membres</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="plus" class="w-4 h-4" />
                Ajouter un abonnement
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
                <p class="text-xs text-muted-foreground mb-2">Actifs</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">MRR estimé</p>
                <p class="text-2xl font-bold text-accent">{{ number_format($stats['mrr'], 2) }} $</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Annulés</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des abonnements</h2>
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

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="active" @selected($status === 'active')>Actif</option>
                        <option value="cancelled" @selected($status === 'cancelled')>Annulé</option>
                        <option value="expired" @selected($status === 'expired')>Expiré</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.payments.subscriptions.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="abonnement(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} abonnement(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.payments.subscriptions.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Plan</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Prix</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Cycle</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Prochain paiement</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subscriptions as $subscription)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $subscription->id }}"
                                                @change="$event.target.checked ? selected.push({{ $subscription->id }}) : selected = selected.filter(i => i !== {{ $subscription->id }})"
                                                :checked="selected.includes({{ $subscription->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-foreground">{{ $subscription->user->name ?? '—' }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $subscription->user->email ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 font-medium">{{ $subscription->plan }}</td>
                                        <td class="px-6 py-4">{{ number_format($subscription->price, 2) }} $</td>
                                        <td class="px-6 py-4">{{ $subscription->billingCycleLabel() }}</td>
                                        <td class="px-6 py-4">{{ $subscription->next_billing_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ match($subscription->status) { 'active' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400', 'cancelled' => 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400', default => 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' } }}">
                                                {{ $subscription->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $subscription->id }}, user_id: {{ $subscription->user_id }}, plan: @js($subscription->plan), price: {{ $subscription->price }}, billing_cycle: @js($subscription->billing_cycle), status: @js($subscription->status), started_at: @js($subscription->started_at?->format('Y-m-d')), next_billing_at: @js($subscription->next_billing_at?->format('Y-m-d')) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.payments.subscriptions.destroy', $subscription) }}" onsubmit="return confirm('Supprimer cet abonnement ?');">
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
                                        <td colspan="8" class="px-6 py-8 text-center text-muted-foreground">Aucun abonnement trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter un abonnement">
            <form method="POST" action="{{ route('admin.payments.subscriptions.store') }}" class="space-y-4">
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
                    <x-input-label for="create_plan" value="Plan" />
                    <x-text-input id="create_plan" name="plan" type="text" class="mt-1 block w-full" placeholder="ex: Pro, Premium" required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_price" value="Prix ($)" />
                        <x-text-input id="create_price" name="price" type="number" min="0" step="0.01" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="create_billing_cycle" value="Cycle" />
                        <select id="create_billing_cycle" name="billing_cycle" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="monthly">Mensuel</option>
                            <option value="yearly">Annuel</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="create_started_at" value="Date de début" />
                        <x-text-input id="create_started_at" name="started_at" type="date" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="create_next_billing_at" value="Prochain paiement" />
                        <x-text-input id="create_next_billing_at" name="next_billing_at" type="date" class="mt-1 block w-full" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="active">Actif</option>
                            <option value="cancelled">Annulé</option>
                            <option value="expired">Expiré</option>
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
        <x-modal open="editOpen" title="Modifier l'abonnement">
            <form method="POST" :action="`{{ url('admin/payments/subscriptions') }}/${editing.id}`" class="space-y-4">
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
                    <x-input-label for="edit_plan" value="Plan" />
                    <input id="edit_plan" name="plan" type="text" x-model="editing.plan" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_price" value="Prix ($)" />
                        <input id="edit_price" name="price" type="number" min="0" step="0.01" x-model="editing.price" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                    </div>
                    <div>
                        <x-input-label for="edit_billing_cycle" value="Cycle" />
                        <select id="edit_billing_cycle" name="billing_cycle" x-model="editing.billing_cycle" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="monthly">Mensuel</option>
                            <option value="yearly">Annuel</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_started_at" value="Date de début" />
                        <input id="edit_started_at" name="started_at" type="date" x-model="editing.started_at" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_next_billing_at" value="Prochain paiement" />
                        <input id="edit_next_billing_at" name="next_billing_at" type="date" x-model="editing.next_billing_at" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="active">Actif</option>
                            <option value="cancelled">Annulé</option>
                            <option value="expired">Expiré</option>
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
