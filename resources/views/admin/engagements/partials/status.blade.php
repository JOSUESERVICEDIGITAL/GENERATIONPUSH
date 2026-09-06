@php
    $classes = [
        'pending' => 'bg-amber-50 text-amber-700',
        'reviewing' => 'bg-blue-50 text-blue-700',
        'accepted' => 'bg-green-50 text-green-700',
        'rejected' => 'bg-red-50 text-red-700',
    ];

    $labels = [
        'pending' => 'En attente',
        'reviewing' => 'En étude',
        'accepted' => 'Acceptée',
        'rejected' => 'Refusée',
    ];
@endphp

<span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $classes[$status] ?? 'bg-gray-100 text-gray-600' }}">
    {{ $labels[$status] ?? ucfirst($status) }}
</span>
