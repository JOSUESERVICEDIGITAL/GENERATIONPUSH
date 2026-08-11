<x-layouts.admin title="Fondatrice">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Page Fondatrice</h1>
            <p class="text-muted-foreground mt-1">Gère la page publique dédiée à la fondatrice : photos, biographie, mission, réseaux sociaux.</p>
        </div>
        <a href="{{ route('front.founder') }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-accent hover:underline shrink-0">
            <x-icon name="globe" class="w-4 h-4" /> Voir la page publique
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm mt-6">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.pages.founder.update') }}" enctype="multipart/form-data" class="space-y-6 mt-6">
        @csrf
        @method('PUT')

        <!-- Activation générale -->
        <div class="bg-card border border-border rounded-xl p-6">
            <x-toggle name="is_page_enabled" label="Afficher la page publique de la fondatrice" :checked="$founder->is_page_enabled" />
            <p class="text-xs text-muted-foreground mt-1">Si désactivé, la page <code>/fondatrice</code> renvoie une 404 côté visiteurs.</p>
        </div>

        <!-- Identité -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <h2 class="font-semibold text-foreground flex items-center gap-2">
                <x-icon name="users-round" class="w-4 h-4 text-accent" /> Identité
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="name" value="Nom complet" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $founder->name)" />
                </div>
                <div>
                    <x-input-label for="role_title" value="Titre / Fonction" />
                    <x-text-input id="role_title" name="role_title" type="text" class="mt-1 block w-full" :value="old('role_title', $founder->role_title)" placeholder="ex: Fondatrice & Directrice générale" />
                </div>
            </div>
            <div>
                <x-input-label for="main_photo" value="Photo principale (portrait, bien cadrée)" />
                @if ($founder->mainPhotoUrl())
                    <img src="{{ $founder->mainPhotoUrl() }}" class="h-32 w-32 object-cover rounded-xl mt-2 mb-2 border border-border">
                @endif
                <input id="main_photo" name="main_photo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                <p class="text-xs text-muted-foreground mt-1">Format portrait recommandé (ex: 800×1000px), bien cadrée sur le visage.</p>
            </div>
        </div>

        <!-- Biographie -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-foreground flex items-center gap-2">
                    <x-icon name="file-text" class="w-4 h-4 text-accent" /> Biographie
                </h2>
                <x-toggle name="show_bio" :checked="$founder->show_bio" label="Visible" />
            </div>
            <textarea name="bio" rows="5" class="block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" placeholder="Parcours, expérience, ce qui la définit...">{{ old('bio', $founder->bio) }}</textarea>
        </div>

        <!-- Pourquoi elle a créé Generation PUSH -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-foreground flex items-center gap-2">
                    <x-icon name="zap" class="w-4 h-4 text-accent" /> Pourquoi elle a créé Generation PUSH
                </h2>
                <x-toggle name="show_why_founded" :checked="$founder->show_why_founded" label="Visible" />
            </div>
            <textarea name="why_founded" rows="5" class="block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" placeholder="L'histoire, le déclic, la motivation de départ...">{{ old('why_founded', $founder->why_founded) }}</textarea>
        </div>

        <!-- Mission -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-foreground flex items-center gap-2">
                    <x-icon name="award" class="w-4 h-4 text-accent" /> Sa mission
                </h2>
                <x-toggle name="show_mission" :checked="$founder->show_mission" label="Visible" />
            </div>
            <textarea name="mission" rows="5" class="block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" placeholder="Ce qu'elle veut accomplir, sa vision pour Generation PUSH...">{{ old('mission', $founder->mission) }}</textarea>
        </div>

        <!-- Réseaux sociaux -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-foreground flex items-center gap-2">
                    <x-icon name="globe" class="w-4 h-4 text-accent" /> Réseaux sociaux
                </h2>
                <x-toggle name="show_social" :checked="$founder->show_social" label="Visible" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="facebook_url" value="Facebook" />
                    <x-text-input id="facebook_url" name="facebook_url" type="url" class="mt-1 block w-full" :value="old('facebook_url', $founder->facebook_url)" />
                </div>
                <div>
                    <x-input-label for="instagram_url" value="Instagram" />
                    <x-text-input id="instagram_url" name="instagram_url" type="url" class="mt-1 block w-full" :value="old('instagram_url', $founder->instagram_url)" />
                </div>
                <div>
                    <x-input-label for="twitter_url" value="X / Twitter" />
                    <x-text-input id="twitter_url" name="twitter_url" type="url" class="mt-1 block w-full" :value="old('twitter_url', $founder->twitter_url)" />
                </div>
                <div>
                    <x-input-label for="linkedin_url" value="LinkedIn" />
                    <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full" :value="old('linkedin_url', $founder->linkedin_url)" />
                </div>
                <div>
                    <x-input-label for="youtube_url" value="YouTube" />
                    <x-text-input id="youtube_url" name="youtube_url" type="url" class="mt-1 block w-full" :value="old('youtube_url', $founder->youtube_url)" />
                </div>
            </div>
        </div>

        <!-- Interrupteur galerie -->
        <div class="bg-card border border-border rounded-xl p-6">
            <x-toggle name="show_gallery" :checked="$founder->show_gallery" label="Afficher le carrousel de photos sur la page publique" />
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                Enregistrer les modifications
            </button>
        </div>
    </form>

    <!-- Galerie de photos (carrousel) — formulaire séparé, upload MULTIPLE -->
    <div class="bg-card border border-border rounded-xl p-6 space-y-4 mt-6">
        <h2 class="font-semibold text-foreground flex items-center gap-2">
            <x-icon name="image" class="w-4 h-4 text-accent" /> Galerie du carrousel (change toutes les 3 secondes sur la page publique)
        </h2>
        <p class="text-xs text-muted-foreground -mt-2">Tu peux sélectionner plusieurs photos à la fois (maintiens Ctrl ou Cmd en cliquant dans le sélecteur de fichiers).</p>

        <form method="POST" action="{{ route('admin.pages.founder.photos.store') }}" enctype="multipart/form-data" class="flex items-center gap-3">
            @csrf
            <input type="file" name="photos[]" accept="image/*" multiple required class="block flex-1 text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
            <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground text-sm font-medium hover:opacity-90 transition-all duration-200 shrink-0">
                Ajouter
            </button>
        </form>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
            @forelse ($founder->photos as $photo)
                <div class="relative group aspect-square rounded-lg overflow-hidden border border-border">
                    <img src="{{ $photo->imageUrl() }}" class="w-full h-full object-cover">
                    <form method="POST" action="{{ route('admin.pages.founder.photos.destroy', $photo) }}" onsubmit="return confirm('Supprimer cette photo ?');" class="absolute top-1.5 end-1.5">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-7 h-7 rounded-full bg-black/60 hover:bg-destructive flex items-center justify-center transition-colors duration-200">
                            <x-icon name="trash" class="w-3.5 h-3.5 text-white" />
                        </button>
                    </form>
                </div>
            @empty
                <p class="col-span-full text-sm text-muted-foreground py-4 text-center">Aucune photo dans la galerie pour l'instant. La photo principale sera utilisée seule sur la page publique.</p>
            @endforelse
        </div>
    </div>
</x-layouts.admin>
