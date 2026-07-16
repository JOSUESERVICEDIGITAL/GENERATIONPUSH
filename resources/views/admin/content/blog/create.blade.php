<x-layouts.admin title="Nouvel article">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Nouvel article</h1>
        <p class="text-muted-foreground mt-1">Rédige un nouvel article pour le blog.</p>
    </div>

    <form method="POST" action="{{ route('admin.content.blog.store') }}" enctype="multipart/form-data" class="bg-card border border-border rounded-xl p-6 space-y-6">
        @csrf

        @include('admin.content.blog._form')

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                Publier l'article
            </button>
            <a href="{{ route('admin.content.blog.index') }}" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">
                Annuler
            </a>
        </div>
    </form>
</x-layouts.admin>
