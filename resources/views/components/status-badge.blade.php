@props(['status'])

@php
    $styles = match ($status) {
        'active' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400',
        'inactive' => 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400',
        'suspended' => 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400',
        default => 'bg-gray-100 text-gray-700',
    };
    $label = match ($status) {
        'active' => 'Actif',
        'inactive' => 'Inactif',
        'suspended' => 'Suspendu',
        default => $status,
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-semibold $styles"]) }}>
    {{ $label }}
</span>
