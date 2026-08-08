<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" x-init="$watch('dark', v => localStorage.setItem('theme', v ? 'dark' : 'light'))" :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <x-seo-meta :title="$title ?? null" :description="$description ?? null" :image="$image ?? null" :type="$type ?? 'website'" :noindex="$noindex ?? false" />

    <x-schema.organization />
    {!! $schema ?? '' !!}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased min-h-screen flex flex-col">

    <x-navigation />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-footer />

</body>
</html>
