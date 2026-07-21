@props([])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-2.5 bg-destructive border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-destructive focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 cursor-pointer']) }}>
    {{ $slot }}
</button>
