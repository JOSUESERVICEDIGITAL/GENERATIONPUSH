<x-layouts.admin title="Vidéos">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($videos->pluck('id')),
            openEdit(video) {
                this.editing = video;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Vidéos</h1>
                <p class="text-muted-foreground mt-2">Gère la vidéothèque de Generation PUSH</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="film" class="w-4 h-4" />
                Ajouter une vidéo
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Publiées</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Vues cumulées</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['views'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Toutes les vidéos</h2>
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
                            placeholder="Rechercher une vidéo..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="published" @selected($status === 'published')>Publiée</option>
                        <option value="draft" @selected($status === 'draft')>Brouillon</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.media.videos.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <x-bulk-action-bar count="selected.length" label="vidéo(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} vidéo(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.media.videos.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <!-- Grille -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($videos as $video)
                        <div class="border border-border rounded-lg overflow-hidden bg-background">
                            <div class="aspect-video bg-secondary relative flex items-center justify-center">
                                @if ($video->thumbnailUrl())
                                    <img src="{{ $video->thumbnailUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <x-icon name="film" class="w-10 h-10 text-muted-foreground" />
                                @endif
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                    <x-icon name="play-circle" class="w-10 h-10 text-white" />
                                </div>
                                <label class="absolute top-2 left-2">
                                    <input
                                        type="checkbox"
                                        class="rounded border-border"
                                        value="{{ $video->id }}"
                                        @change="$event.target.checked ? selected.push({{ $video->id }}) : selected = selected.filter(i => i !== {{ $video->id }})"
                                        :checked="selected.includes({{ $video->id }})"
                                    >
                                </label>
                                @if ($video->duration)
                                    <span class="absolute bottom-2 right-2 px-1.5 py-0.5 rounded bg-black/70 text-white text-[10px]">{{ $video->duration }}</span>
                                @endif
                            </div>
                            <div class="p-3 space-y-2">
                                <h3 class="font-semibold text-foreground text-sm line-clamp-1">{{ $video->title }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $video->status === 'published' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' }}">{{ $video->statusLabel() }}</span>
                                    <span class="text-[10px] text-muted-foreground">{{ $video->views }} vues</span>
                                </div>
                                <div class="flex items-center gap-2 pt-1">
                                    <button
                                        type="button"
                                        @click="openEdit({ id: {{ $video->id }}, title: @js($video->title), description: @js($video->description), video_url: @js($video->video_url), duration: @js($video->duration), status: @js($video->status), thumbnail_url: @js($video->thumbnailUrl()) })"
                                        class="flex-1 px-2 py-1 rounded-lg border border-border text-xs text-foreground hover:bg-secondary transition-all duration-200"
                                    >
                                        Modifier
                                    </button>
                                    <form method="POST" action="{{ route('admin.media.videos.destroy', $video) }}" onsubmit="return confirm('Supprimer cette vidéo ?');">
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
                        <div class="col-span-full text-center text-muted-foreground py-8">Aucune vidéo trouvée</div>
                    @endforelse
                </div>

                <div>
                    {{ $videos->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter une vidéo">
            <form method="POST" action="{{ route('admin.media.videos.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_title" value="Titre" />
                    <x-text-input id="create_title" name="title" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_description" value="Description" />
                    <textarea id="create_description" name="description" rows="3" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                </div>
                <div>
                    <x-input-label for="create_video_url" value="Lien vidéo (YouTube, Vimeo...)" />
                    <x-text-input id="create_video_url" name="video_url" type="url" class="mt-1 block w-full" placeholder="https://..." />
                </div>
                <div>
                    <x-input-label for="create_file" value="Ou fichier vidéo (MP4, max 100 Mo)" />
                    <input id="create_file" name="file" type="file" accept="video/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="create_thumbnail" value="Miniature" />
                    <input id="create_thumbnail" name="thumbnail" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_duration" value="Durée" />
                        <x-text-input id="create_duration" name="duration" type="text" class="mt-1 block w-full" placeholder="ex: 12:34" />
                    </div>
                    <div>
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="published">Publiée</option>
                            <option value="draft">Brouillon</option>
                        </select>
                    </div>
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
        <x-modal open="editOpen" title="Modifier la vidéo">
            <form method="POST" :action="`{{ url('admin/media/videos') }}/${editing.id}`" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div x-show="editing.thumbnail_url" class="flex justify-center">
                    <img :src="editing.thumbnail_url" class="h-20 rounded-lg object-cover border border-border">
                </div>
                <div>
                    <x-input-label for="edit_title" value="Titre" />
                    <input id="edit_title" name="title" type="text" x-model="editing.title" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_description" value="Description" />
                    <textarea id="edit_description" name="description" rows="3" x-model="editing.description" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                </div>
                <div>
                    <x-input-label for="edit_video_url" value="Lien vidéo" />
                    <input id="edit_video_url" name="video_url" type="url" x-model="editing.video_url" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div>
                    <x-input-label for="edit_file" value="Remplacer le fichier vidéo (optionnel)" />
                    <input id="edit_file" name="file" type="file" accept="video/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="edit_thumbnail" value="Remplacer la miniature (optionnel)" />
                    <input id="edit_thumbnail" name="thumbnail" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_duration" value="Durée" />
                        <input id="edit_duration" name="duration" type="text" x-model="editing.duration" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="published">Publiée</option>
                            <option value="draft">Brouillon</option>
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
