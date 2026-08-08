@if ($heroSlides->isNotEmpty())
    <section x-data="{ active: 0, count: {{ $heroSlides->count() }} }" x-init="setInterval(() => active = (active + 1) % count, 6000)" class="relative overflow-hidden">
        @foreach ($heroSlides as $slide)
            <div x-show="active === {{ $loop->index }}" x-cloak class="relative">
                <a href="{{ $slide->link_url ?? '#' }}">
                    <img src="{{ Storage::url($slide->image_path) }}" alt="{{ $slide->title ?? 'Field Forecast' }}"
                         @if($loop->first) fetchpriority="high" @else loading="lazy" @endif
                         class="w-full h-56 sm:h-80 object-cover">
                </a>
                @if ($slide->title)
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center bg-black/30 text-white px-4">
                        <h2 class="text-2xl sm:text-3xl font-bold">{{ $slide->title }}</h2>
                        @if ($slide->subtitle)
                            <p class="mt-1">{{ $slide->subtitle }}</p>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </section>
@endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight">
        {!! nl2br(e($section->content['headline'] ?? "Smarter sports predictions,\nbacked by data.")) !!}
    </h1>
    <p class="mt-4 text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
        {{ $section->content['subheadline'] ?? 'Field Forecast publishes daily match predictions, odds information, and expert analysis across football, basketball, tennis, rugby, cricket and esports.' }}
    </p>
    <div class="mt-8 flex items-center justify-center gap-4">
        @guest
            <a href="{{ route('register') }}"><x-button variant="primary">Get started free</x-button></a>
            <a href="{{ route('login') }}"><x-button variant="secondary">Log in</x-button></a>
        @else
            <a href="{{ route('dashboard') }}"><x-button variant="primary">Go to dashboard</x-button></a>
        @endguest
    </div>

    {{-- Sport category quick links --}}
    <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
        @foreach ($sports as $sport)
            <a href="{{ route('sports.show', $sport) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium bg-white dark:bg-slate-900 ring-1 ring-slate-900/5 dark:ring-white/10 hover:ring-indigo-500">
                {{ $sport->name }}
            </a>
        @endforeach
    </div>
</div>
