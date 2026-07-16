@props(['status'])

@php
    $styles = match ($status) {
        'active' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400',
        'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400',
        'completed' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400',
        default => 'bg-gray-100 text-gray-700',
    };
    $label = match ($status) {
        'active' => 'En cours',
        'draft' => 'Brouillon',
        'completed' => 'Terminé',
        default => $status,
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-semibold $styles"]) }}>
    {{ $label }}
</span>
