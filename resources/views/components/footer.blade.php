<footer class="border-t border-slate-200 dark:border-slate-800 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 text-sm text-slate-500 dark:text-slate-400">
        <div class="flex flex-wrap gap-x-6 gap-y-2 mb-6">
            <a href="{{ route('articles.index') }}" class="hover:underline">Blog</a>
            <a href="{{ route('faq.index') }}" class="hover:underline">FAQ</a>
            @foreach (\App\Models\Page::active()->get(['title', 'slug']) as $page)
                <a href="{{ route('pages.show', $page) }}" class="hover:underline">{{ $page->title }}</a>
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <p>&copy; {{ now()->year }} Field Forecast. All predictions are informational only.</p>
            <p class="max-w-md">
                Field Forecast publishes statistics, analysis, and predictions for informational purposes only.
                We do not accept wagers or facilitate betting of any kind.
            </p>
        </div>
    </div>
</footer>
