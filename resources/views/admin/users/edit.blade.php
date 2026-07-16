<x-layouts.admin title="Modifier l'utilisateur">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Modifier {{ $user->name }}</h1>
        <p class="text-muted-foreground mt-1">Met à jour les informations du compte.</p>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-card border border-border rounded-xl p-6 space-y-6">
        @csrf
        @method('PUT')

        @include('admin.users._form')

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                Enregistrer les modifications
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">
                Annuler
            </a>
        </div>
    </form>
</x-layouts.admin>
