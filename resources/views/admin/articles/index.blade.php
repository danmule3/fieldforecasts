<x-layouts.admin :title="'Articles'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Articles</h2>
        <a href="{{ route('admin.articles.create') }}"><x-button>+ Add article</x-button></a>
    </div>

    <form method="GET" class="mb-4">
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
            <option value="">All statuses</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            <option value="published" @selected(request('status') === 'published')>Published</option>
        </select>
    </form>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Title</th><th class="px-4 py-3">Category</th><th class="px-4 py-3">Author</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($articles as $article)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $article->title }}</td>
                        <td class="px-4 py-3">{{ $article->category->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $article->author->name }}</td>
                        <td class="px-4 py-3">{{ ucfirst($article->status) }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            @if ($article->status === 'published')
                                <a href="{{ route('articles.show', $article) }}" class="text-slate-500">View</a>
                            @endif
                            <a href="{{ route('admin.articles.edit', $article) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="inline" onsubmit="return confirm('Delete this article?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $articles->links() }}
</x-layouts.admin>
