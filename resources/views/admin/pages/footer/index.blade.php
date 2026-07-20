<x-layouts.admin title="Pied de page">
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
                <h1 class="text-2xl font-bold text-foreground">Pied de page (footer)</h1>
                <p class="text-muted-foreground mt-1">Gère les liens affichés dans le pied de page du site public, groupés par colonne.</p>
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

        <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900 text-blue-700 dark:text-blue-400 px-4 py-3 rounded-lg text-sm">
            ℹ️ Les coordonnées de contact et les réseaux sociaux du footer se gèrent dans <a href="{{ route('admin.pages.home.edit') }}" class="underline font-medium">Pages → Accueil</a>. Ici, tu gères uniquement les liens (colonnes de liens rapides).
        </div>

        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Colonnes de liens</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($items as $column => $links)
                    <div class="border border-border rounded-lg p-4 space-y-2">
                        <h3 class="font-semibold text-foreground text-sm">{{ $column }}</h3>
                        @foreach ($links as $link)
                            <div class="flex items-center justify-between">
                                <div class="min-w-0">
                                    <p class="text-sm text-foreground truncate">{{ $link->label }}</p>
                                    <p class="text-xs text-muted-foreground truncate">{{ $link->url }}</p>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <button type="button" @click="openEdit({ id: {{ $link->id }}, label: @js($link->label), url: @js($link->url), footer_column: @js($link->footer_column), order: {{ $link->order }}, status: @js($link->status) })" class="p-1.5 hover:bg-secondary rounded-lg transition-all duration-200">
                                        <x-icon name="pencil" class="w-3.5 h-3.5 text-muted-foreground hover:text-foreground" />
                                    </button>
                                    <form method="POST" action="{{ route('admin.pages.footer.destroy', $link) }}" onsubmit="return confirm('Supprimer ce lien ?');">
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
                @empty
                    <p class="col-span-full text-center text-muted-foreground py-6">Aucun lien de pied de page pour l'instant</p>
                @endforelse
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter un lien de pied de page">
            <form method="POST" action="{{ route('admin.pages.footer.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="location" value="footer">
                <div>
                    <x-input-label for="create_label" value="Texte du lien" />
                    <x-text-input id="create_label" name="label" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_url" value="URL" />
                    <x-text-input id="create_url" name="url" type="text" class="mt-1 block w-full" placeholder="/a-propos ou https://..." required />
                </div>
                <div>
                    <x-input-label for="create_footer_column" value="Colonne" />
                    <x-text-input id="create_footer_column" name="footer_column" type="text" class="mt-1 block w-full" placeholder="ex: Liens rapides, Ressources, Légal" />
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
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">Ajouter</button>
                    <button type="button" @click="createOpen = false" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">Annuler</button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Édition -->
        <x-modal open="editOpen" title="Modifier le lien">
            <form method="POST" :action="`{{ url('admin/pages/footer') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="location" value="footer">
                <div>
                    <x-input-label for="edit_label" value="Texte du lien" />
                    <input id="edit_label" name="label" type="text" x-model="editing.label" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_url" value="URL" />
                    <input id="edit_url" name="url" type="text" x-model="editing.url" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_footer_column" value="Colonne" />
                    <input id="edit_footer_column" name="footer_column" type="text" x-model="editing.footer_column" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
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
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">Enregistrer</button>
                    <button type="button" @click="editOpen = false" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">Annuler</button>
                </div>
            </form>
        </x-modal>
    </div>
</x-layouts.admin>
