<x-layouts.admin title="Nouvelle page">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Nouvelle page</h1>
        <p class="text-muted-foreground mt-1">Crée une nouvelle page statique pour le site public.</p>
    </div>

    <form method="POST" action="{{ route('admin.pages.custom.store') }}" class="bg-card border border-border rounded-xl p-6 space-y-6">
        @csrf
        @include('admin.pages.custom._form')
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">Créer la page</button>
            <a href="{{ route('admin.pages.custom.index') }}" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">Annuler</a>
        </div>
    </form>
</x-layouts.admin>
