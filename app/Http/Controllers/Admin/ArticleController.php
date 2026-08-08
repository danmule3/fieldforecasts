<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends AdminController
{
    public function __construct(private readonly ImageOptimizer $imageOptimizer)
    {
    }

    public function index(Request $request): View
    {
        $articles = Article::with(['category', 'author'])
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.articles.index', ['articles' => $articles]);
    }

    public function create(): View
    {
        return view('admin.articles.form', $this->formData(new Article()));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
        $data['author_id'] = $request->user()->id;

        if ($data['status'] === Article::STATUS_PUBLISHED && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image_path'] = $this->imageOptimizer->storeOptimized($request->file('featured_image'), 'articles', 'public');
        }

        $tags = $this->resolveTags($request);
        unset($data['tags']);

        $article = Article::create($data);
        $article->tags()->sync($tags);

        return redirect()->route('admin.articles.index')->with('status', 'Article created.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.form', $this->formData($article));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validated($request);

        if ($data['status'] === Article::STATUS_PUBLISHED && empty($article->published_at) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            if ($article->featured_image_path) {
                Storage::disk('public')->delete($article->featured_image_path);
            }
            $data['featured_image_path'] = $this->imageOptimizer->storeOptimized($request->file('featured_image'), 'articles', 'public');
        }

        $tags = $this->resolveTags($request);
        unset($data['tags']);

        $article->update($data);
        $article->tags()->sync($tags);

        return redirect()->route('admin.articles.index')->with('status', 'Article updated.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.articles.index')->with('status', 'Article deleted.');
    }

    private function formData(Article $article): array
    {
        return [
            'article' => $article,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'selectedTags' => $article->exists ? $article->tags->pluck('name')->join(', ') : '',
        ];
    }

    /** Free-text comma-separated tag input, creating any tags that don't exist yet — avoids forcing editors to pre-create tags elsewhere first. */
    private function resolveTags(Request $request): array
    {
        $names = collect(explode(',', (string) $request->input('tags')))
            ->map(fn ($t) => trim($t))
            ->filter()
            ->unique();

        return $names->map(function ($name) {
            return Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name])->id;
        })->all();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:150'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
        ]);
    }
}
