<x-layouts.admin title="Sponsors">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($sponsors->pluck('id')),
            openEdit(sponsor) {
                this.editing = sponsor;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Sponsors</h1>
                <p class="text-muted-foreground mt-2">Gère les partenaires et sponsors de Generation PUSH</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="award" class="w-4 h-4" />
                Ajouter un sponsor
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
                <p class="text-xs text-muted-foreground mb-2">Platine</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['platinum'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Or</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['gold'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des sponsors</h2>
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
                            placeholder="Rechercher un sponsor..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="tier" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les tiers</option>
                        <option value="platinum" @selected($tier === 'platinum')>Platine</option>
                        <option value="gold" @selected($tier === 'gold')>Or</option>
                        <option value="silver" @selected($tier === 'silver')>Argent</option>
                        <option value="bronze" @selected($tier === 'bronze')>Bronze</option>
                    </select>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="active" @selected($status === 'active')>Actif</option>
                        <option value="inactive" @selected($status === 'inactive')>Inactif</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $tier || $status)
                        <a href="{{ route('admin.partners.sponsors.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="sponsor(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} sponsor(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.partners.sponsors.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <!-- Grille -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse ($sponsors as $sponsor)
                        <div class="border border-border rounded-lg overflow-hidden bg-background">
                            <div class="aspect-square bg-secondary relative flex items-center justify-center p-4">
                                @if ($sponsor->logoUrl())
                                    <img src="{{ $sponsor->logoUrl() }}" alt="{{ $sponsor->name }}" class="max-w-full max-h-full object-contain">
                                @else
                                    <x-icon name="award" class="w-10 h-10 text-muted-foreground" />
                                @endif
                                <label class="absolute top-2 left-2">
                                    <input
                                        type="checkbox"
                                        class="rounded border-border"
                                        value="{{ $sponsor->id }}"
                                        @change="$event.target.checked ? selected.push({{ $sponsor->id }}) : selected = selected.filter(i => i !== {{ $sponsor->id }})"
                                        :checked="selected.includes({{ $sponsor->id }})"
                                    >
                                </label>
                            </div>
                            <div class="p-3 space-y-2">
                                <h3 class="font-semibold text-foreground text-sm line-clamp-1">{{ $sponsor->name }}</h3>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-accent/10 text-accent">{{ $sponsor->tierLabel() }}</span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $sponsor->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' }}">{{ $sponsor->statusLabel() }}</span>
                                </div>
                                <div class="flex items-center gap-2 pt-1">
                                    <button
                                        type="button"
                                        @click="openEdit({ id: {{ $sponsor->id }}, name: @js($sponsor->name), website_url: @js($sponsor->website_url), tier: @js($sponsor->tier), status: @js($sponsor->status), order: {{ $sponsor->order }}, logo_url: @js($sponsor->logoUrl()) })"
                                        class="flex-1 px-2 py-1 rounded-lg border border-border text-xs text-foreground hover:bg-secondary transition-all duration-200"
                                    >
                                        Modifier
                                    </button>
                                    <form method="POST" action="{{ route('admin.partners.sponsors.destroy', $sponsor) }}" onsubmit="return confirm('Supprimer ce sponsor ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg border border-border hover:bg-secondary transition-all duration-200">
                                            <x-icon name="trash" class="w-3.5 h-3.5 text-muted-foreground hover:text-destructive" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-muted-foreground py-8">Aucun sponsor trouvé</div>
                    @endforelse
                </div>

                <div>
                    {{ $sponsors->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter un sponsor">
            <form method="POST" action="{{ route('admin.partners.sponsors.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_name" value="Nom du sponsor" />
                    <x-text-input id="create_name" name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_logo" value="Logo" />
                    <input id="create_logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="create_website_url" value="Site web" />
                    <x-text-input id="create_website_url" name="website_url" type="url" class="mt-1 block w-full" placeholder="https://..." />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_tier" value="Tier" />
                        <select id="create_tier" name="tier" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="platinum">Platine</option>
                            <option value="gold">Or</option>
                            <option value="silver">Argent</option>
                            <option value="bronze" selected>Bronze</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="create_order" value="Ordre d'affichage" />
                        <x-text-input id="create_order" name="order" type="number" min="0" class="mt-1 block w-full" value="0" />
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
        <x-modal open="editOpen" title="Modifier le sponsor">
            <form method="POST" :action="`{{ url('admin/partners/sponsors') }}/${editing.id}`" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div x-show="editing.logo_url" class="flex justify-center">
                    <img :src="editing.logo_url" class="h-20 rounded-lg object-contain border border-border p-2">
                </div>
                <div>
                    <x-input-label for="edit_name" value="Nom du sponsor" />
                    <input id="edit_name" name="name" type="text" x-model="editing.name" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_logo" value="Remplacer le logo (optionnel)" />
                    <input id="edit_logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="edit_website_url" value="Site web" />
                    <input id="edit_website_url" name="website_url" type="url" x-model="editing.website_url" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_tier" value="Tier" />
                        <select id="edit_tier" name="tier" x-model="editing.tier" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="platinum">Platine</option>
                            <option value="gold">Or</option>
                            <option value="silver">Argent</option>
                            <option value="bronze">Bronze</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="edit_order" value="Ordre d'affichage" />
                        <input id="edit_order" name="order" type="number" min="0" x-model="editing.order" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
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
