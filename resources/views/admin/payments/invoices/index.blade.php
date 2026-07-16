<x-layouts.admin title="Factures">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($invoices->pluck('id')),
            openEdit(invoice) {
                this.editing = invoice;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Factures</h1>
                <p class="text-muted-foreground mt-2">Gère les factures émises à tes utilisateurs</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="file-text" class="w-4 h-4" />
                Créer une facture
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
                <p class="text-xs text-muted-foreground mb-2">Payées</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['paid'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Montant en attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_amount'], 2) }} $</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">En retard</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des factures</h2>
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
                            placeholder="N° facture, utilisateur..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="paid" @selected($status === 'paid')>Payée</option>
                        <option value="pending" @selected($status === 'pending')>En attente</option>
                        <option value="overdue" @selected($status === 'overdue')>En retard</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.payments.invoices.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="facture(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} facture(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.payments.invoices.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">N° Facture</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Utilisateur</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Montant</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Émise le</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Échéance</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoices as $invoice)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $invoice->id }}"
                                                @change="$event.target.checked ? selected.push({{ $invoice->id }}) : selected = selected.filter(i => i !== {{ $invoice->id }})"
                                                :checked="selected.includes({{ $invoice->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs font-medium text-foreground">{{ $invoice->invoice_number }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-foreground">{{ $invoice->user->name ?? '—' }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $invoice->user->email ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 font-semibold">{{ number_format($invoice->amount, 2) }} $</td>
                                        <td class="px-6 py-4">{{ $invoice->issued_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $invoice->due_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ match($invoice->status) { 'paid' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400', 'overdue' => 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400', default => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-400' } }}">
                                                {{ $invoice->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $invoice->id }}, user_id: {{ $invoice->user_id }}, amount: {{ $invoice->amount }}, status: @js($invoice->status), issued_at: @js($invoice->issued_at->format('Y-m-d')), due_at: @js($invoice->due_at?->format('Y-m-d')) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.payments.invoices.destroy', $invoice) }}" onsubmit="return confirm('Supprimer cette facture ?');">
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
                                        <td colspan="8" class="px-6 py-8 text-center text-muted-foreground">Aucune facture trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Créer une facture">
            <form method="POST" action="{{ route('admin.payments.invoices.store') }}" class="space-y-4">
                @csrf
                <p class="text-xs text-muted-foreground">Le numéro de facture est généré automatiquement (ex: INV-00001).</p>
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
                    <x-input-label for="create_amount" value="Montant ($)" />
                    <x-text-input id="create_amount" name="amount" type="number" min="0" step="0.01" class="mt-1 block w-full" required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_issued_at" value="Date d'émission" />
                        <x-text-input id="create_issued_at" name="issued_at" type="date" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="create_due_at" value="Date d'échéance" />
                        <x-text-input id="create_due_at" name="due_at" type="date" class="mt-1 block w-full" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="pending">En attente</option>
                            <option value="paid">Payée</option>
                            <option value="overdue">En retard</option>
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
        <x-modal open="editOpen" title="Modifier la facture">
            <form method="POST" :action="`{{ url('admin/payments/invoices') }}/${editing.id}`" class="space-y-4">
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
                    <x-input-label for="edit_amount" value="Montant ($)" />
                    <input id="edit_amount" name="amount" type="number" min="0" step="0.01" x-model="editing.amount" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_issued_at" value="Date d'émission" />
                        <input id="edit_issued_at" name="issued_at" type="date" x-model="editing.issued_at" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                    </div>
                    <div>
                        <x-input-label for="edit_due_at" value="Date d'échéance" />
                        <input id="edit_due_at" name="due_at" type="date" x-model="editing.due_at" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="pending">En attente</option>
                            <option value="paid">Payée</option>
                            <option value="overdue">En retard</option>
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
