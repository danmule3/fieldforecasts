<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends AdminController
{
    public function __construct(private readonly CommentService $comments)
    {
    }

    public function index(Request $request): View
    {
        $comments = Comment::with(['user', 'article'])
            ->when($request->status, fn ($q, $v) => $q->where('status', $v), fn ($q) => $q->pending())
            ->orderBy('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.comments.index', ['comments' => $comments]);
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $this->comments->approve($comment);

        return back()->with('status', 'Comment approved.');
    }

    public function spam(Comment $comment): RedirectResponse
    {
        $this->comments->markSpam($comment);

        return back()->with('status', 'Comment marked as spam.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('status', 'Comment deleted.');
    }
}
