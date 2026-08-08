<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(private readonly ArticleService $articles)
    {
    }

    public function index(Request $request): View
    {
        return view('articles.index', [
            'articles' => $this->articles->paginateFiltered($request->only(['category', 'tag', 'search'])),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->status === Article::STATUS_PUBLISHED, 404);

        $article->increment('views_count');
        $article->load(['category', 'author', 'tags']);
        $article->load(['approvedComments' => fn ($q) => $q->with(['user', 'replies.user'])->latest()]);

        return view('articles.show', [
            'article' => $article,
            'related' => $this->articles->related($article),
        ]);
    }
}
