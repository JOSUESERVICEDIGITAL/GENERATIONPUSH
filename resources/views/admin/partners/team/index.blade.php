<x-layouts.admin title="Équipe">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($members->pluck('id')),
            openEdit(member) {
                this.editing = member;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Équipe</h1>
                <p class="text-muted-foreground mt-2">Gère les membres de l'équipe Generation PUSH</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="users-round" class="w-4 h-4" />
                Ajouter un membre
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
                <p class="text-xs text-muted-foreground mb-2">Total</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Actifs</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Membres de l'équipe</h2>
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
                            placeholder="Rechercher un membre..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="active" @selected($status === 'active')>Actif</option>
                        <option value="inactive" @selected($status === 'inactive')>Inactif</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.partners.team.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="membre(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} membre(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.partners.team.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <!-- Grille -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($members as $member)
                        <div class="border border-border rounded-lg overflow-hidden bg-background p-4 flex gap-4">
                            <div class="relative shrink-0">
                                <div class="w-16 h-16 rounded-full bg-secondary overflow-hidden flex items-center justify-center">
                                    @if ($member->photoUrl())
                                        <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="font-bold text-foreground">{{ Str::of($member->name)->explode(' ')->map(fn($w) => Str::substr($w, 0, 1))->take(2)->join('') }}</span>
                                    @endif
                                </div>
                                <input
                                    type="checkbox"
                                    class="absolute -top-1 -left-1 rounded border-border"
                                    value="{{ $member->id }}"
                                    @change="$event.target.checked ? selected.push({{ $member->id }}) : selected = selected.filter(i => i !== {{ $member->id }})"
                                    :checked="selected.includes({{ $member->id }})"
                                >
                            </div>
                            <div class="flex-1 min-w-0 space-y-1">
                                <h3 class="font-semibold text-foreground text-sm">{{ $member->name }}</h3>
                                <p class="text-xs text-muted-foreground">{{ $member->role ?? '—' }}</p>
                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $member->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' }}">
                                    {{ $member->statusLabel() }}
                                </span>
                                <div class="flex items-center gap-2 pt-2">
                                    <button
                                        type="button"
                                        @click="openEdit({ id: {{ $member->id }}, name: @js($member->name), role: @js($member->role), bio: @js($member->bio), email: @js($member->email), linkedin_url: @js($member->linkedin_url), order: {{ $member->order }}, status: @js($member->status), photo_url: @js($member->photoUrl()) })"
                                        class="px-2 py-1 rounded-lg border border-border text-xs text-foreground hover:bg-secondary transition-all duration-200"
                                    >
                                        Modifier
                                    </button>
                                    <form method="POST" action="{{ route('admin.partners.team.destroy', $member) }}" onsubmit="return confirm('Supprimer ce membre ?');">
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
                        <div class="col-span-full text-center text-muted-foreground py-8">Aucun membre trouvé</div>
                    @endforelse
                </div>

                <div>
                    {{ $members->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter un membre de l'équipe">
            <form method="POST" action="{{ route('admin.partners.team.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_name" value="Nom complet" />
                    <x-text-input id="create_name" name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_role" value="Rôle / Poste" />
                    <x-text-input id="create_role" name="role" type="text" class="mt-1 block w-full" placeholder="ex: Directrice des programmes" />
                </div>
                <div>
                    <x-input-label for="create_photo" value="Photo" />
                    <input id="create_photo" name="photo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="create_bio" value="Bio" />
                    <textarea id="create_bio" name="bio" rows="3" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_email" value="Email" />
                        <x-text-input id="create_email" name="email" type="email" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="create_linkedin_url" value="LinkedIn" />
                        <x-text-input id="create_linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full" placeholder="https://linkedin.com/in/..." />
                    </div>
                    <div>
                        <x-input-label for="create_order" value="Ordre d'affichage" />
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
        <x-modal open="editOpen" title="Modifier le membre">
            <form method="POST" :action="`{{ url('admin/partners/team') }}/${editing.id}`" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div x-show="editing.photo_url" class="flex justify-center">
                    <img :src="editing.photo_url" class="w-20 h-20 rounded-full object-cover border border-border">
                </div>
                <div>
                    <x-input-label for="edit_name" value="Nom complet" />
                    <input id="edit_name" name="name" type="text" x-model="editing.name" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_role" value="Rôle / Poste" />
                    <input id="edit_role" name="role" type="text" x-model="editing.role" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div>
                    <x-input-label for="edit_photo" value="Remplacer la photo (optionnel)" />
                    <input id="edit_photo" name="photo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="edit_bio" value="Bio" />
                    <textarea id="edit_bio" name="bio" rows="3" x-model="editing.bio" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_email" value="Email" />
                        <input id="edit_email" name="email" type="email" x-model="editing.email" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_linkedin_url" value="LinkedIn" />
                        <input id="edit_linkedin_url" name="linkedin_url" type="url" x-model="editing.linkedin_url" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_order" value="Ordre d'affichage" />
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
