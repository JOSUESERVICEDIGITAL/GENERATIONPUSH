<x-layouts.admin title="Témoignages">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($testimonials->pluck('id')),
            createFeatured: false,
            editFeatured: false,
            openEdit(testimonial) {
                this.editing = testimonial;
                this.editFeatured = testimonial.featured;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Témoignages</h1>
                <p class="text-muted-foreground mt-2">Gère les avis et témoignages de la communauté</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="quote" class="w-4 h-4" />
                Ajouter un témoignage
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
                <p class="text-xs text-muted-foreground mb-2">Publiés</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Mis en avant</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['featured'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Note moyenne</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['avg_rating'] }} / 5</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des témoignages</h2>
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
                            placeholder="Rechercher par auteur..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="published" @selected($status === 'published')>Publié</option>
                        <option value="draft" @selected($status === 'draft')>Brouillon</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.partners.testimonials.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="témoignage(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} témoignage(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.partners.testimonials.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <!-- Liste -->
                <div class="space-y-3">
                    @forelse ($testimonials as $testimonial)
                        <div class="border border-border rounded-lg p-4 flex gap-4">
                            <input
                                type="checkbox"
                                class="rounded border-border mt-1 shrink-0"
                                value="{{ $testimonial->id }}"
                                @change="$event.target.checked ? selected.push({{ $testimonial->id }}) : selected = selected.filter(i => i !== {{ $testimonial->id }})"
                                :checked="selected.includes({{ $testimonial->id }})"
                            >
                            <div class="w-12 h-12 rounded-full bg-secondary overflow-hidden flex items-center justify-center shrink-0">
                                @if ($testimonial->photoUrl())
                                    <img src="{{ $testimonial->photoUrl() }}" alt="{{ $testimonial->author_name }}" class="w-full h-full object-cover">
                                @else
                                    <x-icon name="quote" class="w-5 h-5 text-muted-foreground" />
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 class="font-semibold text-foreground text-sm">{{ $testimonial->author_name }}</h3>
                                        <p class="text-xs text-muted-foreground">{{ $testimonial->author_role ?? '—' }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <x-icon name="star" class="w-3.5 h-3.5 {{ $i <= $testimonial->rating ? 'text-yellow-500' : 'text-muted-foreground' }}" style="{{ $i <= $testimonial->rating ? 'fill: currentColor' : '' }}" />
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-sm text-muted-foreground mt-2 line-clamp-2">{{ $testimonial->content }}</p>
                                <div class="flex items-center gap-2 mt-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $testimonial->status === 'published' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' }}">
                                        {{ $testimonial->statusLabel() }}
                                    </span>
                                    @if ($testimonial->featured)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-accent/10 text-accent">Mis en avant</span>
                                    @endif
                                    <button
                                        type="button"
                                        @click="openEdit({ id: {{ $testimonial->id }}, author_name: @js($testimonial->author_name), author_role: @js($testimonial->author_role), content: @js($testimonial->content), rating: {{ $testimonial->rating }}, featured: {{ $testimonial->featured ? 'true' : 'false' }}, status: @js($testimonial->status), photo_url: @js($testimonial->photoUrl()) })"
                                        class="ml-auto px-2 py-1 rounded-lg border border-border text-xs text-foreground hover:bg-secondary transition-all duration-200"
                                    >
                                        Modifier
                                    </button>
                                    <form method="POST" action="{{ route('admin.partners.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Supprimer ce témoignage ?');">
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
                        <div class="text-center text-muted-foreground py-8">Aucun témoignage trouvé</div>
                    @endforelse
                </div>

                <div>
                    {{ $testimonials->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter un témoignage">
            <form method="POST" action="{{ route('admin.partners.testimonials.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_author_name" value="Nom de l'auteur" />
                    <x-text-input id="create_author_name" name="author_name" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_author_role" value="Rôle / Fonction" />
                    <x-text-input id="create_author_role" name="author_role" type="text" class="mt-1 block w-full" placeholder="ex: CEO chez XYZ" />
                </div>
                <div>
                    <x-input-label for="create_photo" value="Photo (optionnel)" />
                    <input id="create_photo" name="photo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="create_content" value="Témoignage" />
                    <textarea id="create_content" name="content" rows="4" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_rating" value="Note" />
                        <select id="create_rating" name="rating" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="5" selected>5 étoiles</option>
                            <option value="4">4 étoiles</option>
                            <option value="3">3 étoiles</option>
                            <option value="2">2 étoiles</option>
                            <option value="1">1 étoile</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="published">Publié</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input id="create_featured" name="featured" type="checkbox" value="1" x-model="createFeatured" class="rounded border-border">
                    <x-input-label for="create_featured" value="Mettre en avant" class="!mb-0" />
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
        <x-modal open="editOpen" title="Modifier le témoignage">
            <form method="POST" :action="`{{ url('admin/partners/testimonials') }}/${editing.id}`" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div x-show="editing.photo_url" class="flex justify-center">
                    <img :src="editing.photo_url" class="w-16 h-16 rounded-full object-cover border border-border">
                </div>
                <div>
                    <x-input-label for="edit_author_name" value="Nom de l'auteur" />
                    <input id="edit_author_name" name="author_name" type="text" x-model="editing.author_name" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_author_role" value="Rôle / Fonction" />
                    <input id="edit_author_role" name="author_role" type="text" x-model="editing.author_role" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div>
                    <x-input-label for="edit_photo" value="Remplacer la photo (optionnel)" />
                    <input id="edit_photo" name="photo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="edit_content" value="Témoignage" />
                    <textarea id="edit_content" name="content" rows="4" x-model="editing.content" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_rating" value="Note" />
                        <select id="edit_rating" name="rating" x-model="editing.rating" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="5">5 étoiles</option>
                            <option value="4">4 étoiles</option>
                            <option value="3">3 étoiles</option>
                            <option value="2">2 étoiles</option>
                            <option value="1">1 étoile</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="published">Publié</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input id="edit_featured" name="featured" type="checkbox" value="1" x-model="editFeatured" class="rounded border-border">
                    <x-input-label for="edit_featured" value="Mettre en avant" class="!mb-0" />
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
