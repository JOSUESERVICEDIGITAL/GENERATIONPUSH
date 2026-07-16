<x-layouts.admin title="Dashboard">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Bonjour {{ auth()->user()->name ?? 'Admin' }} 👋</h1>
        <p class="text-muted-foreground mt-1">Voici un aperçu de l'activité Generation PUSH aujourd'hui.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($kpis as $kpi)
            <x-kpi-card
                :label="$kpi['label']"
                :value="$kpi['value']"
                :trend="$kpi['trend']"
                :up="$kpi['up']"
                :icon="$kpi['icon']"
            />
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 bg-card border border-border rounded-lg p-5">
            <h2 class="font-semibold text-foreground mb-4">Dernières inscriptions</h2>
            <p class="text-sm text-muted-foreground">À connecter au modèle <code>User</code> une fois les migrations créées.</p>
        </div>
        <div class="bg-card border border-border rounded-lg p-5">
            <h2 class="font-semibold text-foreground mb-4">Derniers paiements</h2>
            <p class="text-sm text-muted-foreground">À connecter au modèle <code>Transaction</code>.</p>
        </div>
    </div>
</x-layouts.admin>
