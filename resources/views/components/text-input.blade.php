@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-200 focus:border-accent focus:ring-accent rounded-lg shadow-sm w-full']) !!}>
