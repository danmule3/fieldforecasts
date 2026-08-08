@if ($section->title || $section->description)
    <section class="py-12 bg-slate-100/60 dark:bg-slate-900/60">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if ($section->title)
                <h2 class="text-2xl font-bold mb-4">{{ $section->title }}</h2>
            @endif
            @if ($section->description)
                <p class="text-slate-600 dark:text-slate-300 whitespace-pre-line">{{ $section->description }}</p>
            @endif
        </div>
    </section>
@endif
