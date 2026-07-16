<x-layouts.admin title="Catégories">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($categories->pluck('id')),
            openEdit(category) {
                this.editing = category;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Catégories</h1>
                <p class="text-muted-foreground mt-2">Organise les articles du blog par thématique</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="tag" class="w-4 h-4" />
                Ajouter une catégorie
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total catégories</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total articles</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['posts'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des catégories</h2>
            </div>

            <div class="p-6 space-y-4">
                <form method="GET" class="flex items-center gap-3">
                    <div class="relative flex-1 max-w-sm">
                        <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Rechercher une catégorie..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>
                    @if ($search)
                        <a href="{{ route('admin.content.categories.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <x-bulk-action-bar count="selected.length" label="catégorie(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} catégorie(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.content.categories.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($categories as $category)
                        <div class="border border-border rounded-lg p-4 flex items-start gap-3">
                            <input
                                type="checkbox"
                                class="rounded border-border mt-1 shrink-0"
                                value="{{ $category->id }}"
                                @change="$event.target.checked ? selected.push({{ $category->id }}) : selected = selected.filter(i => i !== {{ $category->id }})"
                                :checked="selected.includes({{ $category->id }})"
                            >
                            <div class="w-3 h-3 rounded-full shrink-0 mt-1.5" style="background-color: {{ $category->color }}"></div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-foreground text-sm">{{ $category->name }}</h3>
                                <p class="text-xs text-muted-foreground mt-1 line-clamp-2">{{ $category->description ?? '—' }}</p>
                                <p class="text-xs text-muted-foreground mt-2">{{ $category->posts_count }} article(s)</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <button
                                        type="button"
                                        @click="openEdit({ id: {{ $category->id }}, name: @js($category->name), description: @js($category->description), color: @js($category->color) })"
                                        class="px-2 py-1 rounded-lg border border-border text-xs text-foreground hover:bg-secondary transition-all duration-200"
                                    >
                                        Modifier
                                    </button>
                                    <form method="POST" action="{{ route('admin.content.categories.destroy', $category) }}" onsubmit="return confirm('Supprimer cette catégorie ?');">
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
                        <div class="col-span-full text-center text-muted-foreground py-8">Aucune catégorie trouvée</div>
                    @endforelse
                </div>

                <div>
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter une catégorie">
            <form method="POST" action="{{ route('admin.content.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_name" value="Nom" />
                    <x-text-input id="create_name" name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_description" value="Description" />
                    <textarea id="create_description" name="description" rows="2" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                </div>
                <div>
                    <x-input-label for="create_color" value="Couleur" />
                    <input id="create_color" name="color" type="color" value="#E8631A" class="mt-1 block w-16 h-10 rounded-lg border-border cursor-pointer">
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
        <x-modal open="editOpen" title="Modifier la catégorie">
            <form method="POST" :action="`{{ url('admin/content/categories') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="edit_name" value="Nom" />
                    <input id="edit_name" name="name" type="text" x-model="editing.name" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_description" value="Description" />
                    <textarea id="edit_description" name="description" rows="2" x-model="editing.description" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                </div>
                <div>
                    <x-input-label for="edit_color" value="Couleur" />
                    <input id="edit_color" name="color" type="color" x-model="editing.color" class="mt-1 block w-16 h-10 rounded-lg border-border cursor-pointer">
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
