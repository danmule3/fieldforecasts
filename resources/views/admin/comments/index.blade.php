<x-layouts.admin :title="'Comments'">
    <h2 class="text-lg font-semibold mb-6">Comment moderation</h2>

    <form method="GET" class="mb-4">
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
            <option value="pending" @selected(request('status', 'pending') === 'pending')>Pending</option>
            <option value="approved" @selected(request('status') === 'approved')>Approved</option>
            <option value="spam" @selected(request('status') === 'spam')>Spam</option>
        </select>
    </form>

    <div class="space-y-4 mb-4">
        @forelse ($comments as $comment)
            <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4">
                <div class="flex justify-between mb-2">
                    <div>
                        <p class="text-sm font-medium">{{ $comment->user->name }} <span class="text-slate-400 font-normal">on</span>
                            <a href="{{ route('articles.show', $comment->article) }}" class="text-indigo-600 dark:text-indigo-400">{{ $comment->article->title }}</a>
                        </p>
                        <p class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300 mb-3">{{ $comment->body }}</p>

                <div class="flex gap-3 text-sm">
                    @if ($comment->status !== 'approved')
                        <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                            @csrf @method('PATCH')
                            <button class="text-emerald-600 dark:text-emerald-400">Approve</button>
                        </form>
                    @endif
                    @if ($comment->status !== 'spam')
                        <form method="POST" action="{{ route('admin.comments.spam', $comment) }}">
                            @csrf @method('PATCH')
                            <button class="text-amber-600 dark:text-amber-400">Mark spam</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment?');">
                        @csrf @method('DELETE')
                        <button class="text-red-600 dark:text-red-400">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-500 dark:text-slate-400">No comments in this queue.</p>
        @endforelse
    </div>
    {{ $comments->links() }}
</x-layouts.admin>
