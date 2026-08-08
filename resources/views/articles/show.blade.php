<x-layouts.app :title="$article->meta_title ?? $article->title" :description="$article->meta_description ?? $article->excerpt" type="article" :image="$article->featured_image_path">
    <x-slot:schema>
        <x-schema.article :article="$article" />
    </x-slot:schema>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-breadcrumbs :items="array_filter([
            ['label' => 'Blog', 'url' => route('articles.index')],
            $article->category ? ['label' => $article->category->name, 'url' => route('articles.index', ['category' => $article->category->slug])] : null,
            ['label' => $article->title, 'url' => null],
        ])" />

        <h1 class="text-3xl font-bold mb-2">{{ $article->title }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
            By {{ $article->author->name }} &middot; {{ $article->published_at->format('d M Y') }}
        </p>

        @if ($article->featured_image_path)
            <img src="{{ Storage::url($article->featured_image_path) }}" alt="{{ $article->title }}" loading="lazy" class="w-full rounded-2xl mb-8">
        @endif

        <div class="prose dark:prose-invert max-w-none mb-8">
            {!! nl2br(e($article->body)) !!}
        </div>

        @if ($article->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-10">
                @foreach ($article->tags as $tag)
                    <a href="{{ route('articles.index', ['tag' => $tag->slug]) }}" class="text-xs rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1">#{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif

        @if ($related->isNotEmpty())
            <div class="mb-10">
                <h2 class="font-semibold mb-4">Related articles</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($related as $item)
                        <a href="{{ route('articles.show', $item) }}" class="block bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4 hover:ring-indigo-500 transition">
                            <p class="text-sm font-medium">{{ $item->title }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <section id="comments">
            <h2 class="font-semibold mb-4">Comments ({{ $article->approvedComments->count() }})</h2>

            @session('status')
                @if (session('status') === 'comment-pending')
                    <x-alert type="info" class="mb-4">Your comment was submitted and is awaiting moderation.</x-alert>
                @elseif (session('status') === 'comment-posted')
                    <x-alert type="success" class="mb-4">Comment posted.</x-alert>
                @endif
            @endsession

            @auth
                <form method="POST" action="{{ route('comments.store', $article) }}" class="mb-8">
                    @csrf
                    <textarea name="body" rows="3" placeholder="Add a comment..." required
                              class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm mb-2"></textarea>
                    <x-button type="submit">Post comment</x-button>
                </form>
            @else
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">
                    <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400">Log in</a> to leave a comment.
                </p>
            @endauth

            <div class="space-y-4">
                @forelse ($article->approvedComments as $comment)
                    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4">
                        <p class="text-sm font-medium">{{ $comment->user->name }}</p>
                        <p class="text-xs text-slate-400 mb-2">{{ $comment->created_at->diffForHumans() }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $comment->body }}</p>

                        @foreach ($comment->replies as $reply)
                            <div class="mt-3 ml-4 pl-4 border-l-2 border-slate-100 dark:border-slate-800">
                                <p class="text-sm font-medium">{{ $reply->user->name }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $reply->body }}</p>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <p class="text-sm text-slate-500 dark:text-slate-400">No comments yet — be the first.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.app>
