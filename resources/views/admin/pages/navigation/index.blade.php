<x-layouts.admin title="Navigation">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            openEdit(item) {
                this.editing = item;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-foreground">Navigation (menu du site public)</h1>
                <p class="text-muted-foreground mt-1">Gère les liens affichés dans la barre de navigation du frontoffice.</p>
            </div>
            <button @click="createOpen = true" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                <x-icon name="plus" class="w-4 h-4" />
                Ajouter un lien
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liens de la navbar</h2>
            </div>
            <div class="p-6 space-y-3">
                @forelse ($items as $item)
                    <div class="border border-border rounded-lg">
                        <div class="flex items-center justify-between p-3">
                            <div class="flex items-center gap-3">
                                <x-icon name="menu" class="w-4 h-4 text-muted-foreground" />
                                <div>
                                    <p class="font-medium text-foreground text-sm">{{ $item->label }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $item->url }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $item->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' }}">
                                    {{ $item->status === 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="openEdit({ id: {{ $item->id }}, parent_id: null, label: @js($item->label), url: @js($item->url), open_in_new_tab: {{ $item->open_in_new_tab ? 'true' : 'false' }}, order: {{ $item->order }}, status: @js($item->status) })" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                </button>
                                <form method="POST" action="{{ route('admin.pages.navigation.destroy', $item) }}" onsubmit="return confirm('Supprimer ce lien ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                        <x-icon name="trash" class="w-4 h-4 text-muted-foreground hover:text-destructive" />
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if ($item->children->isNotEmpty())
                            <div class="border-t border-border ps-8 pe-3 py-2 space-y-2">
                                @foreach ($item->children as $child)
                                    <div class="flex items-center justify-between py-1">
                                        <div class="flex items-center gap-2">
                                            <x-icon name="chevron-right" class="w-3 h-3 text-muted-foreground" />
                                            <p class="text-sm text-foreground">{{ $child->label }}</p>
                                            <p class="text-xs text-muted-foreground">{{ $child->url }}</p>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="openEdit({ id: {{ $child->id }}, parent_id: {{ $item->id }}, label: @js($child->label), url: @js($child->url), open_in_new_tab: {{ $child->open_in_new_tab ? 'true' : 'false' }}, order: {{ $child->order }}, status: @js($child->status) })" class="p-1.5 hover:bg-secondary rounded-lg transition-all duration-200">
                                                <x-icon name="pencil" class="w-3.5 h-3.5 text-muted-foreground hover:text-foreground" />
                                            </button>
                                            <form method="POST" action="{{ route('admin.pages.navigation.destroy', $child) }}" onsubmit="return confirm('Supprimer ce lien ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 hover:bg-secondary rounded-lg transition-all duration-200">
                                                    <x-icon name="trash" class="w-3.5 h-3.5 text-muted-foreground hover:text-destructive" />
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-muted-foreground py-6">Aucun lien de navigation pour l'instant</p>
                @endforelse
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter un lien de navigation">
            <form method="POST" action="{{ route('admin.pages.navigation.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="location" value="navbar">
                <div>
                    <x-input-label for="create_label" value="Texte du lien" />
                    <x-text-input id="create_label" name="label" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_url" value="URL" />
                    <x-text-input id="create_url" name="url" type="text" class="mt-1 block w-full" placeholder="/programmes ou https://..." required />
                </div>
                <div>
                    <x-input-label for="create_parent_id" value="Sous-menu de (optionnel)" />
                    <select id="create_parent_id" name="parent_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="">— Lien principal —</option>
                        @foreach ($topLevelItems as $top)
                            <option value="{{ $top->id }}">{{ $top->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_order" value="Ordre" />
                        <x-text-input id="create_order" name="order" type="number" min="0" class="mt-1 block w-full" value="0" />
                    </div>
                    <div>
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input id="create_open_in_new_tab" name="open_in_new_tab" type="checkbox" value="1" class="rounded border-border">
                    <x-input-label for="create_open_in_new_tab" value="Ouvrir dans un nouvel onglet" class="!mb-0" />
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">Ajouter</button>
                    <button type="button" @click="createOpen = false" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">Annuler</button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Édition -->
        <x-modal open="editOpen" title="Modifier le lien">
            <form method="POST" :action="`{{ url('admin/pages/navigation') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="location" value="navbar">
                <div>
                    <x-input-label for="edit_label" value="Texte du lien" />
                    <input id="edit_label" name="label" type="text" x-model="editing.label" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_url" value="URL" />
                    <input id="edit_url" name="url" type="text" x-model="editing.url" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_parent_id" value="Sous-menu de" />
                    <select id="edit_parent_id" name="parent_id" x-model="editing.parent_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="">— Lien principal —</option>
                        @foreach ($topLevelItems as $top)
                            <option value="{{ $top->id }}">{{ $top->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_order" value="Ordre" />
                        <input id="edit_order" name="order" type="number" min="0" x-model="editing.order" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input id="edit_open_in_new_tab" name="open_in_new_tab" type="checkbox" value="1" x-model="editing.open_in_new_tab" class="rounded border-border">
                    <x-input-label for="edit_open_in_new_tab" value="Ouvrir dans un nouvel onglet" class="!mb-0" />
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">Enregistrer</button>
                    <button type="button" @click="editOpen = false" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">Annuler</button>
                </div>
            </form>
        </x-modal>
    </div>
</x-layouts.admin>
