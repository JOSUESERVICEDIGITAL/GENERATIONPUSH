<x-layouts.admin :title="$type === 'partner' ? 'Devenir partenaire' : 'Devenir bénévole'">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-foreground">{{ $type === 'partner' ? 'Page Devenir Partenaire' : 'Page Devenir Bénévole' }}</h1>
            <p class="text-muted-foreground mt-1">Gère le contenu de cette page publique et son formulaire d'appel à candidature.</p>
        </div>
        <a href="{{ route($type === 'partner' ? 'front.partner' : 'front.volunteer') }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-accent hover:underline shrink-0">
            <x-icon name="globe" class="w-4 h-4" /> Voir la page publique
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm mt-6">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.pages.engagement.update', $type) }}" enctype="multipart/form-data" class="space-y-6 mt-6">
        @csrf
        @method('PUT')

        <div class="bg-card border border-border rounded-xl p-6">
            <x-toggle name="is_visible" label="Afficher cette page publiquement" :checked="$page->is_visible" />
        </div>

        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="title" value="Titre" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $page->title)" />
                </div>
                <div>
                    <x-input-label for="subtitle" value="Sous-titre" />
                    <x-text-input id="subtitle" name="subtitle" type="text" class="mt-1 block w-full" :value="old('subtitle', $page->subtitle)" />
                </div>
            </div>
            <div>
                <x-input-label for="description" value="Description" />
                <textarea name="description" rows="4" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">{{ old('description', $page->description) }}</textarea>
            </div>
            <div>
                <x-input-label for="benefits" value="Avantages (un par ligne)" />
                <textarea name="benefits" rows="4" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" placeholder="Visibilité auprès de 2500+ membres&#10;Accès aux événements en avant-première&#10;...">{{ old('benefits', $page->benefits) }}</textarea>
            </div>
            <div>
                <x-input-label for="image" value="Image d'illustration" />
                @if ($page->imageUrl())
                    <img src="{{ $page->imageUrl() }}" class="h-32 rounded-lg object-cover mt-2 mb-2 border border-border">
                @endif
                <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
            </div>
            <div>
                <x-input-label for="cta_label" value="Texte du bouton d'appel" />
                <x-text-input id="cta_label" name="cta_label" type="text" class="mt-1 block w-full" :value="old('cta_label', $page->cta_label)" />
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</x-layouts.admin>
