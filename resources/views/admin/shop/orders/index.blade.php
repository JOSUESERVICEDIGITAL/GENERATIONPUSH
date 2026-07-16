<x-layouts.admin title="Commandes">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: { id: null, user_id: '', status: 'pending' },
            selected: [],
            allIds: @js($orders->pluck('id')),
            products: @js($products),
            createItems: [{ product_id: '', quantity: 1 }],
            editItems: [],
            productPrice(id) {
                const p = this.products.find(p => p.id === Number(id));
                if (!p) return 0;
                return p.is_free ? 0 : parseFloat(p.price);
            },
            createTotal() {
                return this.createItems.reduce((sum, i) => sum + this.productPrice(i.product_id) * (i.quantity || 0), 0);
            },
            editTotal() {
                return this.editItems.reduce((sum, i) => sum + this.productPrice(i.product_id) * (i.quantity || 0), 0);
            },
            openEdit(order) {
                this.editing = { id: order.id, user_id: order.user_id, status: order.status };
                this.editItems = order.items.length ? order.items : [{ product_id: '', quantity: 1 }];
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Commandes</h1>
                <p class="text-muted-foreground mt-2">Suis les achats de produits de la boutique</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="shopping-bag" class="w-4 h-4" />
                Créer une commande
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($products->isEmpty())
            <div class="bg-yellow-50 dark:bg-yellow-950/30 border border-yellow-200 dark:border-yellow-900 text-yellow-700 dark:text-yellow-400 px-4 py-3 rounded-lg text-sm">
                Publie d'abord au moins un produit dans <a href="{{ route('admin.shop.products.index') }}" class="underline font-medium">Boutique → Produits</a> avant de créer des commandes.
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
                <p class="text-xs text-muted-foreground mb-2">Revenu</p>
                <p class="text-2xl font-bold text-accent">{{ number_format($stats['revenue'], 2) }} $</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">En attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des commandes</h2>
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
                            placeholder="N° commande, utilisateur..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="paid" @selected($status === 'paid')>Payée</option>
                        <option value="pending" @selected($status === 'pending')>En attente</option>
                        <option value="cancelled" @selected($status === 'cancelled')>Annulée</option>
                        <option value="refunded" @selected($status === 'refunded')>Remboursée</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.shop.orders.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="commande(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} commande(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.shop.orders.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">N° Commande</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Utilisateur</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Produits</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Total</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $order->id }}"
                                                @change="$event.target.checked ? selected.push({{ $order->id }}) : selected = selected.filter(i => i !== {{ $order->id }})"
                                                :checked="selected.includes({{ $order->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs font-medium text-foreground">{{ $order->order_number }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-foreground">{{ $order->user->name ?? '—' }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $order->user->email ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-muted-foreground">
                                            {{ $order->items->pluck('title')->take(2)->implode(', ') }}
                                            @if ($order->items->count() > 2)
                                                <span class="text-xs">+{{ $order->items->count() - 2 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-semibold">{{ number_format($order->total, 2) }} $</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ match($order->status) { 'paid' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400', 'cancelled', 'refunded' => 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400', default => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-400' } }}">
                                                {{ $order->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $order->id }}, user_id: {{ $order->user_id }}, status: @js($order->status), items: @js($order->items->map(fn($i) => ['product_id' => $i->product_id, 'quantity' => $i->quantity])) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.shop.orders.destroy', $order) }}" onsubmit="return confirm('Supprimer cette commande ?');">
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
                                        <td colspan="8" class="px-6 py-8 text-center text-muted-foreground">Aucune commande trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Créer une commande">
            <form method="POST" action="{{ route('admin.shop.orders.store') }}" class="space-y-4">
                @csrf
                <p class="text-xs text-muted-foreground">Le numéro de commande est généré automatiquement (ex: ORD-00001).</p>
                <div>
                    <x-input-label for="create_user_id" value="Utilisateur" />
                    <select id="create_user_id" name="user_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                        <option value="">— Choisir —</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <x-input-label value="Produits" />
                    <template x-for="(item, index) in createItems" :key="index">
                        <div class="flex items-center gap-2">
                            <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" class="flex-1 rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                                <option value="">— Choisir un produit —</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }} — {{ $p->is_free ? 'Gratuit' : number_format($p->price, 2) . ' $' }}</option>
                                @endforeach
                            </select>
                            <input :name="'items[' + index + '][quantity]'" type="number" min="1" x-model="item.quantity" class="w-20 rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                            <button type="button" @click="createItems.splice(index, 1)" x-show="createItems.length > 1" class="p-2 hover:bg-secondary rounded-lg">
                                <x-icon name="x" class="w-4 h-4 text-muted-foreground hover:text-destructive" />
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="createItems.push({ product_id: '', quantity: 1 })" class="text-sm text-accent font-medium hover:underline">
                        + Ajouter un produit
                    </button>
                </div>

                <div class="flex justify-between items-center border-t border-border pt-3">
                    <span class="text-sm text-muted-foreground">Total estimé</span>
                    <span class="font-bold text-foreground" x-text="createTotal().toFixed(2) + ' $'"></span>
                </div>

                <div>
                    <x-input-label for="create_status" value="Statut" />
                    <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="pending">En attente</option>
                        <option value="paid">Payée</option>
                        <option value="cancelled">Annulée</option>
                        <option value="refunded">Remboursée</option>
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
        <x-modal open="editOpen" title="Modifier la commande">
            <form method="POST" :action="`{{ url('admin/shop/orders') }}/${editing.id}`" class="space-y-4">
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

                <div class="space-y-2">
                    <x-input-label value="Produits" />
                    <template x-for="(item, index) in editItems" :key="index">
                        <div class="flex items-center gap-2">
                            <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" class="flex-1 rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                                <option value="">— Choisir un produit —</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }} — {{ $p->is_free ? 'Gratuit' : number_format($p->price, 2) . ' $' }}</option>
                                @endforeach
                            </select>
                            <input :name="'items[' + index + '][quantity]'" type="number" min="1" x-model="item.quantity" class="w-20 rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                            <button type="button" @click="editItems.splice(index, 1)" x-show="editItems.length > 1" class="p-2 hover:bg-secondary rounded-lg">
                                <x-icon name="x" class="w-4 h-4 text-muted-foreground hover:text-destructive" />
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="editItems.push({ product_id: '', quantity: 1 })" class="text-sm text-accent font-medium hover:underline">
                        + Ajouter un produit
                    </button>
                </div>

                <div class="flex justify-between items-center border-t border-border pt-3">
                    <span class="text-sm text-muted-foreground">Total estimé</span>
                    <span class="font-bold text-foreground" x-text="editTotal().toFixed(2) + ' $'"></span>
                </div>

                <div>
                    <x-input-label for="edit_status" value="Statut" />
                    <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="pending">En attente</option>
                        <option value="paid">Payée</option>
                        <option value="cancelled">Annulée</option>
                        <option value="refunded">Remboursée</option>
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
