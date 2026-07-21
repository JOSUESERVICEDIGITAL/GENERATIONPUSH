@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} type="checkbox" {!! $attributes->merge(['class' => 'rounded border-gray-300 text-accent shadow-sm focus:ring-accent cursor-pointer']) !!}>
