<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(private readonly CommentService $comments)
    {
    }

    public function store(Request $request, Article $article): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ]);

        $comment = $this->comments->submit($article, $request->user(), $data['body'], $data['parent_id'] ?? null);

        return back()->with('status', $comment->status === 'approved' ? 'comment-posted' : 'comment-pending');
    }
}
