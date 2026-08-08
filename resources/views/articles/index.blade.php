<x-layouts.app :title="__('Blog')" :description="__('Football and sports news, analysis, and platform updates.')">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-6">Blog</h1>

        <form method="GET" class="flex flex-wrap gap-3 mb-8">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles"
                   class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">

            <select name="category" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>

            <x-button type="submit" variant="secondary">Filter</x-button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
            @forelse ($articles as $article)
                <a href="{{ route('articles.show', $article) }}" class="block bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden hover:ring-indigo-500 transition">
                    @if ($article->featured_image_path)
                        <img src="{{ Storage::url($article->featured_image_path) }}" alt="{{ $article->title }}" loading="lazy" class="w-full h-40 object-cover">
                    @endif
                    <div class="p-5">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">
                            {{ $article->category->name ?? 'General' }} &middot; {{ $article->published_at->format('d M Y') }}
                        </p>
                        <h2 class="font-semibold mb-2">{{ $article->title }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ Str::limit($article->excerpt, 120) }}</p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-500 dark:text-slate-400">No articles published yet.</p>
            @endforelse
        </div>

        {{ $articles->links() }}
    </div>
</x-layouts.app>
