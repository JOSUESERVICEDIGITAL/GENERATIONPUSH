@props(['label', 'value', 'trend' => null, 'up' => true, 'icon' => 'layout-dashboard'])

<div class="bg-card border border-border rounded-lg p-5 flex items-start justify-between hover:shadow-sm transition-shadow duration-200">
    <div>
        <p class="text-sm text-muted-foreground">{{ $label }}</p>
        <p class="text-2xl font-bold text-foreground mt-1">{{ $value }}</p>

        @if($trend)
            <p class="text-xs font-medium mt-2 {{ $up ? 'text-success' : 'text-destructive' }}">
                {{ $up ? '↑' : '↓' }} {{ $trend }} <span class="text-muted-foreground font-normal">vs mois dernier</span>
            </p>
        @endif
    </div>

    <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center shrink-0">
        <x-icon :name="$icon" class="w-5 h-5 text-accent" />
    </div>
</div>
