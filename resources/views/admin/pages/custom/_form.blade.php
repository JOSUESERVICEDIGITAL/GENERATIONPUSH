@php $page = $page ?? null; @endphp

<div class="space-y-6">
    <div>
        <x-input-label for="title" value="Titre" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $page?->title)" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="slug" value="URL (slug) — laisser vide pour générer automatiquement" />
        <div class="mt-1 flex items-center gap-2">
            <span class="text-sm text-muted-foreground">/page/</span>
            <x-text-input id="slug" name="slug" type="text" class="block w-full" :value="old('slug', $page?->slug)" placeholder="ex: faq, cgu, confidentialite" />
        </div>
        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="content" value="Contenu" />
        <textarea id="content" name="content" rows="14" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent font-mono text-sm" required>{{ old('content', $page?->content) }}</textarea>
        <p class="text-xs text-muted-foreground mt-1">Texte brut ou HTML simple.</p>
        <x-input-error :messages="$errors->get('content')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Statut" />
        <select id="status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
            <option value="draft" @selected(old('status', $page?->status ?? 'draft') === 'draft')>Brouillon</option>
            <option value="published" @selected(old('status', $page?->status) === 'published')>Publiée</option>
        </select>
    </div>

    <div class="border-t border-border pt-4 grid grid-cols-1 gap-4">
        <div>
            <x-input-label for="meta_title" value="Titre meta (SEO)" />
            <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title', $page?->meta_title)" />
        </div>
        <div>
            <x-input-label for="meta_description" value="Description meta (SEO)" />
            <textarea id="meta_description" name="meta_description" rows="2" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">{{ old('meta_description', $page?->meta_description) }}</textarea>
        </div>
    </div>
</div>
