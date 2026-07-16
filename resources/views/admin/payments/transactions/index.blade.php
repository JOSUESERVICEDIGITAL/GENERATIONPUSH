<x-layouts.admin title="Transactions">
    <div
        x-data="{
            detailOpen: false,
            viewing: {},
            openDetail(transaction) {
                this.viewing = transaction;
                this.detailOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-foreground">Transactions</h1>
            <p class="text-muted-foreground mt-2">Consulte l'historique des paiements</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Confirmées</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total volume</p>
                <p class="text-2xl font-bold text-accent">{{ number_format($stats['volume'], 2) }} $</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">En attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending'], 2) }} $</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Historique des transactions</h2>
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
                        <option value="completed" @selected($status === 'completed')>Confirmé</option>
                        <option value="pending" @selected($status === 'pending')>En attente</option>
                        <option value="failed" @selected($status === 'failed')>Échoué</option>
                    </select>

                    <select name="method" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Toutes les méthodes</option>
                        <option value="stripe" @selected($method === 'stripe')>Stripe</option>
                        <option value="paypal" @selected($method === 'paypal')>PayPal</option>
                        <option value="orange" @selected($method === 'orange')>Orange Money</option>
                        <option value="wave" @selected($method === 'wave')>Wave</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status || $method)
                        <a href="{{ route('admin.payments.transactions.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Tableau -->
                <div class="border border-border rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-secondary border-b border-border">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Utilisateur</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Montant</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Méthode</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Détail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-foreground">{{ $transaction->user->name ?? 'Utilisateur supprimé' }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $transaction->user->email ?? '—' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                @if ($transaction->type === 'payment')
                                                    <x-icon name="arrow-up-right" class="w-4 h-4 text-green-600" />
                                                @else
                                                    <x-icon name="arrow-down-left" class="w-4 h-4 text-blue-600" />
                                                @endif
                                                <span class="font-semibold text-foreground">{{ $transaction->type === 'refund' ? '-' : '+' }}{{ number_format($transaction->amount, 2) }} $</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <x-icon name="credit-card" class="w-4 h-4 text-muted-foreground" />
                                                <span>{{ $transaction->methodLabel() }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><x-transaction-status-badge :status="$transaction->status" /></td>
                                        <td class="px-6 py-4">{{ $transaction->date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <button
                                                type="button"
                                                @click="openDetail({ id: {{ $transaction->id }}, user: @js($transaction->user->name ?? 'Utilisateur supprimé'), email: @js($transaction->user->email ?? '—'), amount: {{ $transaction->amount }}, type: @js($transaction->type), method: @js($transaction->methodLabel()), status: @js($transaction->statusLabel()), date: @js($transaction->date->format('d/m/Y')) })"
                                                class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                            >
                                                <x-icon name="eye" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-muted-foreground">Aucune transaction trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Détail (lecture seule) -->
        <x-modal open="detailOpen" title="Détail de la transaction">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-border">
                    <span class="text-muted-foreground">Utilisateur</span>
                    <span class="font-medium text-foreground" x-text="viewing.user"></span>
                </div>
                <div class="flex justify-between py-2 border-b border-border">
                    <span class="text-muted-foreground">Email</span>
                    <span class="font-medium text-foreground" x-text="viewing.email"></span>
                </div>
                <div class="flex justify-between py-2 border-b border-border">
                    <span class="text-muted-foreground">Montant</span>
                    <span class="font-semibold text-foreground" x-text="(viewing.type === 'refund' ? '-' : '+') + viewing.amount + ' $'"></span>
                </div>
                <div class="flex justify-between py-2 border-b border-border">
                    <span class="text-muted-foreground">Méthode</span>
                    <span class="font-medium text-foreground" x-text="viewing.method"></span>
                </div>
                <div class="flex justify-between py-2 border-b border-border">
                    <span class="text-muted-foreground">Statut</span>
                    <span class="font-medium text-foreground" x-text="viewing.status"></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-muted-foreground">Date</span>
                    <span class="font-medium text-foreground" x-text="viewing.date"></span>
                </div>
            </div>
            <div class="pt-4">
                <button type="button" @click="detailOpen = false" class="w-full px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">
                    Fermer
                </button>
            </div>
        </x-modal>
    </div>
</x-layouts.admin>
