<x-layouts.admin title="Cours">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($courses->pluck('id')),
            openEdit(course) {
                this.editing = course;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Cours</h1>
                <p class="text-muted-foreground mt-2">Gère les leçons vidéo rattachées à chaque formation</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="plus" class="w-4 h-4" />
                Ajouter un cours
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($formations->isEmpty())
            <div class="bg-yellow-50 dark:bg-yellow-950/30 border border-yellow-200 dark:border-yellow-900 text-yellow-700 dark:text-yellow-400 px-4 py-3 rounded-lg text-sm">
                Crée d'abord au moins une formation dans <a href="{{ route('admin.programs.formations.index') }}" class="underline font-medium">Programmes → Formations</a> avant d'ajouter des cours.
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Publiés</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Brouillons</p>
                <p class="text-2xl font-bold text-gray-500">{{ $stats['draft'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Formations couvertes</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['formations'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des cours</h2>
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
                            placeholder="Rechercher un cours..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="formation_id" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Toutes les formations</option>
                        @foreach ($formations as $formation)
                            <option value="{{ $formation->id }}" @selected((string) $formationId === (string) $formation->id)>{{ $formation->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="published" @selected($status === 'published')>Publié</option>
                        <option value="draft" @selected($status === 'draft')>Brouillon</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status || $formationId)
                        <a href="{{ route('admin.programs.courses.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="cours">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} cours ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.programs.courses.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Titre</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Formation</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Durée</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Ordre</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($courses as $course)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $course->id }}"
                                                @change="$event.target.checked ? selected.push({{ $course->id }}) : selected = selected.filter(i => i !== {{ $course->id }})"
                                                :checked="selected.includes({{ $course->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <x-icon name="play-circle" class="w-4 h-4 text-accent" />
                                                <span class="font-medium text-foreground">{{ $course->title }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $course->formation->name ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $course->duration ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $course->order }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $course->status === 'published' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' }}">
                                                {{ $course->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $course->id }}, formation_id: {{ $course->formation_id }}, title: @js($course->title), description: @js($course->description), video_url: @js($course->video_url), duration: @js($course->duration), order: {{ $course->order }}, status: @js($course->status) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.programs.courses.destroy', $course) }}" onsubmit="return confirm('Supprimer ce cours ?');">
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
                                        <td colspan="7" class="px-6 py-8 text-center text-muted-foreground">Aucun cours trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $courses->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter un cours">
            <form method="POST" action="{{ route('admin.programs.courses.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_formation_id" value="Formation" />
                    <select id="create_formation_id" name="formation_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                        <option value="">— Choisir —</option>
                        @foreach ($formations as $formation)
                            <option value="{{ $formation->id }}">{{ $formation->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="create_title" value="Titre du cours" />
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
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="create_duration" value="Durée" />
                        <x-text-input id="create_duration" name="duration" type="text" class="mt-1 block w-full" placeholder="ex: 12 min" />
                    </div>
                    <div>
                        <x-input-label for="create_order" value="Ordre" />
                        <x-text-input id="create_order" name="order" type="number" min="0" class="mt-1 block w-full" value="0" />
                    </div>
                    <div>
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="published">Publié</option>
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
        <x-modal open="editOpen" title="Modifier le cours">
            <form method="POST" :action="`{{ url('admin/programs/courses') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="edit_formation_id" value="Formation" />
                    <select id="edit_formation_id" name="formation_id" x-model="editing.formation_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                        @foreach ($formations as $formation)
                            <option value="{{ $formation->id }}">{{ $formation->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="edit_title" value="Titre du cours" />
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
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="edit_duration" value="Durée" />
                        <input id="edit_duration" name="duration" type="text" x-model="editing.duration" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_order" value="Ordre" />
                        <input id="edit_order" name="order" type="number" min="0" x-model="editing.order" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="published">Publié</option>
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
