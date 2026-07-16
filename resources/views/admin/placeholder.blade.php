<x-layouts.admin :title="$title ?? 'À venir'">
    <div class="bg-card border border-border rounded-lg p-10 text-center">
        <div class="w-14 h-14 mx-auto rounded-lg bg-accent/10 flex items-center justify-center mb-4">
            <x-icon name="inbox" class="w-6 h-6 text-accent" />
        </div>
        <h1 class="text-xl font-bold text-foreground">{{ $title ?? 'Page en construction' }}</h1>
        <p class="text-muted-foreground mt-2">Cette section sera développée à l'étape suivante.</p>
    </div>
</x-layouts.admin>
