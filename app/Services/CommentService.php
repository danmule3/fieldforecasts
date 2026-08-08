<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;

class CommentService
{
    /**
     * Staff comments (Editor+) are auto-approved since they're
     * moderating their own platform; everyone else's comment starts
     * `pending` until an Editor/Admin approves it.
     */
    public function submit(Article $article, User $user, string $body, ?int $parentId = null): Comment
    {
        return Comment::create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'parent_id' => $parentId,
            'body' => $body,
            'status' => $user->isStaff() ? Comment::STATUS_APPROVED : Comment::STATUS_PENDING,
        ]);
    }

    public function approve(Comment $comment): Comment
    {
        $comment->update(['status' => Comment::STATUS_APPROVED]);

        return $comment;
    }

    public function markSpam(Comment $comment): Comment
    {
        $comment->update(['status' => Comment::STATUS_SPAM]);

        return $comment;
    }
}
