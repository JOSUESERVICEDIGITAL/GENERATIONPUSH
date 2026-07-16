@props(['count', 'label' => 'élément(s)'])

<div
    x-show="{{ $count }} > 0"
    x-cloak
    x-transition
    class="flex items-center justify-between bg-accent/10 border border-accent/30 rounded-lg px-4 py-3"
>
    <p class="text-sm font-medium text-foreground">
        <span x-text="{{ $count }}"></span> {{ $label }} sélectionné(s)
    </p>
    <div class="flex items-center gap-2">
        {{ $slot }}
    </div>
</div>
