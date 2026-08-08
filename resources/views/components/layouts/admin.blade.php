<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('adminTheme') !== 'light' }" x-init="$watch('dark', v => localStorage.setItem('adminTheme', v ? 'dark' : 'light'))" :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="robots" content="noindex">
    <title>{{ $title ?? 'Admin' }} | Field Forecast Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <aside class="hidden lg:flex lg:flex-col w-64 border-r border-slate-800 shrink-0">
            <x-admin.sidebar />
        </aside>

        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 flex lg:hidden">
            <div class="fixed inset-0 bg-black/40" @click="sidebarOpen = false"></div>
            <aside class="relative w-64 h-full">
                <x-admin.sidebar />
            </aside>
        </div>

        <div class="flex-1 min-w-0">
            <header class="flex items-center justify-between px-4 sm:px-6 h-16 border-b border-slate-200 dark:border-slate-800 bg-white/60 dark:bg-slate-900/60 backdrop-blur">
                <button class="lg:hidden" @click="sidebarOpen = true" aria-label="Open menu">☰</button>
                <h1 class="font-semibold">{{ $heading ?? ($title ?? 'Admin') }}</h1>
                <div class="flex items-center gap-4">
                    <button @click="dark = !dark" type="button" class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300" aria-label="Toggle dark mode">
                        <span x-show="!dark">🌙</span>
                        <span x-show="dark">☀️</span>
                    </button>
                    <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400">View site &rarr;</a>
                </div>
            </header>

            <main class="p-4 sm:p-6">
                @session('status')
                    <x-alert type="success" class="mb-6">{{ session('status') }}</x-alert>
                @endsession

                @if ($errors->any())
                    <x-alert type="error" class="mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
