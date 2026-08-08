@props(['type' => 'info'])

@php
$styles = [
    'info' => 'bg-indigo-50 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-200 ring-indigo-600/20',
    'success' => 'bg-emerald-50 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-200 ring-emerald-600/20',
    'error' => 'bg-red-50 dark:bg-red-950 text-red-800 dark:text-red-200 ring-red-600/20',
];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg px-4 py-3 text-sm ring-1 ring-inset ' . $styles[$type]]) }} role="alert">
    {{ $slot }}
</div>
