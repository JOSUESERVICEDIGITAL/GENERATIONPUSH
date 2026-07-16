<x-layouts.admin title="Galerie">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($images->pluck('id')),
            openEdit(image) {
                this.editing = image;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Galerie</h1>
                <p class="text-muted-foreground mt-2">Gère les photos des événements et activités</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="image" class="w-4 h-4" />
                Ajouter une image
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
                <p class="text-xs text-muted-foreground mb-2">Total images</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Publiées</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Toutes les images</h2>
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
                            placeholder="Rechercher une image..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    @if ($albums->isNotEmpty())
                        <select name="album" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                            <option value="">Tous les albums</option>
                            @foreach ($albums as $a)
                                <option value="{{ $a }}" @selected($album === $a)>{{ $a }}</option>
                            @endforeach
                        </select>
                    @endif

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $album)
                        <a href="{{ route('admin.media.gallery.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <x-bulk-action-bar count="selected.length" label="image(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} image(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.media.gallery.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <!-- Grille -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    @forelse ($images as $image)
                        <div class="relative group border border-border rounded-lg overflow-hidden bg-background">
                            <div class="aspect-square bg-secondary">
                                @if ($image->imageUrl())
                                    <img src="{{ $image->imageUrl() }}" alt="{{ $image->title }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <label class="absolute top-2 left-2">
                                <input
                                    type="checkbox"
                                    class="rounded border-border"
                                    value="{{ $image->id }}"
                                    @change="$event.target.checked ? selected.push({{ $image->id }}) : selected = selected.filter(i => i !== {{ $image->id }})"
                                    :checked="selected.includes({{ $image->id }})"
                                >
                            </label>
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <p class="text-white text-xs truncate">{{ $image->title ?? '—' }}</p>
                                <div class="flex items-center gap-1 mt-1">
                                    <button
                                        type="button"
                                        @click="openEdit({ id: {{ $image->id }}, title: @js($image->title), album: @js($image->album), status: @js($image->status), image_url: @js($image->imageUrl()) })"
                                        class="p-1 bg-white/20 hover:bg-white/30 rounded"
                                    >
                                        <x-icon name="pencil" class="w-3 h-3 text-white" />
                                    </button>
                                    <form method="POST" action="{{ route('admin.media.gallery.destroy', $image) }}" onsubmit="return confirm('Supprimer cette image ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 bg-white/20 hover:bg-white/30 rounded">
                                            <x-icon name="trash" class="w-3 h-3 text-white" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-muted-foreground py-8">Aucune image trouvée</div>
                    @endforelse
                </div>

                <div>
                    {{ $images->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter une image">
            <form method="POST" action="{{ route('admin.media.gallery.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_image" value="Image" />
                    <input id="create_image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium" required>
                </div>
                <div>
                    <x-input-label for="create_title" value="Titre (optionnel)" />
                    <x-text-input id="create_title" name="title" type="text" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="create_album" value="Album (optionnel)" />
                    <x-text-input id="create_album" name="album" type="text" class="mt-1 block w-full" placeholder="ex: Conférence Bamako 2026" />
                </div>
                <div>
                    <x-input-label for="create_status" value="Statut" />
                    <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="published">Publié</option>
                        <option value="draft">Brouillon</option>
                    </select>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                        Ajouter
                    </button>
                    <button type="button" @click="createOpen = false" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">
                        Annuler
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Édition -->
        <x-modal open="editOpen" title="Modifier l'image">
            <form method="POST" :action="`{{ url('admin/media/gallery') }}/${editing.id}`" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div x-show="editing.image_url" class="flex justify-center">
                    <img :src="editing.image_url" class="h-24 rounded-lg object-cover border border-border">
                </div>
                <div>
                    <x-input-label for="edit_image" value="Remplacer l'image (optionnel)" />
                    <input id="edit_image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="edit_title" value="Titre" />
                    <input id="edit_title" name="title" type="text" x-model="editing.title" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div>
                    <x-input-label for="edit_album" value="Album" />
                    <input id="edit_album" name="album" type="text" x-model="editing.album" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div>
                    <x-input-label for="edit_status" value="Statut" />
                    <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="published">Publié</option>
                        <option value="draft">Brouillon</option>
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
