@props(['status'])

@php
    $styles = match ($status) {
        'upcoming' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'ongoing' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'completed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        default => 'bg-gray-100 text-gray-700',
    };
    $label = match ($status) {
        'upcoming' => 'À venir',
        'ongoing' => 'En cours',
        'completed' => 'Terminée',
        'cancelled' => 'Annulée',
        default => $status,
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-semibold inline-block $styles"]) }}>
    {{ $label }}
</span>
