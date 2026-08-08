<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ArticleService
{
    public function paginateFiltered(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        return Article::query()
            ->with(['category', 'author', 'tags'])
            ->published()
            ->when($filters['category'] ?? null, fn ($q, $v) => $q->whereHas('category', fn ($c) => $c->where('slug', $v)))
            ->when($filters['tag'] ?? null, fn ($q, $v) => $q->whereHas('tags', fn ($t) => $t->where('slug', $v)))
            ->when($filters['search'] ?? null, fn ($q, $v) => $q->search($v))
            ->orderByDesc('published_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function latest(int $limit = 3): Collection
    {
        return Article::query()
            ->with(['category', 'author'])
            ->published()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Related posts: same category first, falling back to shared tags,
     * so an article always has recommendations even with sparse tagging.
     */
    public function related(Article $article, int $limit = 3): Collection
    {
        $byCategory = Article::query()
            ->with(['category', 'author'])
            ->published()
            ->where('id', '!=', $article->id)
            ->when($article->category_id, fn ($q) => $q->where('category_id', $article->category_id))
            ->limit($limit)
            ->get();

        if ($byCategory->count() >= $limit || $article->tags->isEmpty()) {
            return $byCategory;
        }

        $tagIds = $article->tags->pluck('id');
        $byTag = Article::query()
            ->with(['category', 'author'])
            ->published()
            ->where('id', '!=', $article->id)
            ->whereNotIn('id', $byCategory->pluck('id'))
            ->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $tagIds))
            ->limit($limit - $byCategory->count())
            ->get();

        return $byCategory->concat($byTag);
    }
}
