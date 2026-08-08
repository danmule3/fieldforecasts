@if ($testimonials->isNotEmpty())
    <section class="py-8 bg-slate-100/60 dark:bg-slate-900/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold mb-6">{{ $section->title ?? 'What our users say' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ($testimonials as $testimonial)
                    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-3">&ldquo;{{ $testimonial->quote }}&rdquo;</p>
                        <p class="text-sm font-medium">{{ $testimonial->name }}</p>
                        @if ($testimonial->role)
                            <p class="text-xs text-slate-400">{{ $testimonial->role }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
