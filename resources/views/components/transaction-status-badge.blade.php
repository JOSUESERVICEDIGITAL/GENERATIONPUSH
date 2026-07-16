@props(['status'])

@php
    $styles = match ($status) {
        'completed' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400',
        'pending' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-400',
        'failed' => 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400',
        default => 'bg-gray-100 text-gray-700',
    };
    $label = match ($status) {
        'completed' => 'Confirmé',
        'pending' => 'En attente',
        'failed' => 'Échoué',
        default => $status,
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-semibold $styles"]) }}>
    {{ $label }}
</span>
