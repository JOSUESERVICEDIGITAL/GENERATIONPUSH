@php $post = $post ?? null; @endphp

<div class="space-y-6">
    <div>
        <x-input-label for="title" value="Titre" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $post?->title)" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="excerpt" value="Extrait (résumé court)" />
        <textarea id="excerpt" name="excerpt" rows="2" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">{{ old('excerpt', $post?->excerpt) }}</textarea>
        <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="content" value="Contenu" />
        <textarea id="content" name="content" rows="12" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent font-mono text-sm" required>{{ old('content', $post?->content) }}</textarea>
        <p class="text-xs text-muted-foreground mt-1">Texte brut ou HTML simple. Un éditeur enrichi (WYSIWYG) pourra être ajouté plus tard si besoin.</p>
        <x-input-error :messages="$errors->get('content')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="category_id" value="Catégorie" />
            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                <option value="">— Aucune —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $post?->category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="status" value="Statut" />
            <select id="status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                <option value="draft" @selected(old('status', $post?->status ?? 'draft') === 'draft')>Brouillon</option>
                <option value="published" @selected(old('status', $post?->status) === 'published')>Publié</option>
            </select>
        </div>
    </div>

    <div>
        <x-input-label for="cover_image" value="Image de couverture" />
        @if ($post?->coverImageUrl())
            <img src="{{ $post->coverImageUrl() }}" class="h-24 rounded-lg object-cover border border-border mt-2 mb-2">
        @endif
        <input id="cover_image" name="cover_image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
        <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
    </div>
</div>
