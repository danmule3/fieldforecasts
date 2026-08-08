@props(['variant' => 'primary', 'type' => 'submit'])

@php
$variants = [
    'primary' => 'bg-indigo-600 hover:bg-indigo-500 text-white focus:ring-indigo-500',
    'secondary' => 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 focus:ring-slate-400',
    'danger' => 'bg-red-600 hover:bg-red-500 text-white focus:ring-red-500',
];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed ' . $variants[$variant]
    ]) }}
>
    {{ $slot }}
</button>
