<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">{{ $section->title ?? 'Latest articles' }}</h2>
            <a href="{{ route('articles.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400">View all &rarr;</a>
        </div>

        @if ($latestArticles->isEmpty())
            <p class="text-sm text-slate-500 dark:text-slate-400">No articles published yet.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ($latestArticles as $article)
                    <a href="{{ route('articles.show', $article) }}" class="block bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4 hover:ring-indigo-500 transition">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ $article->category->name ?? 'General' }}</p>
                        <p class="font-medium text-sm">{{ $article->title }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
