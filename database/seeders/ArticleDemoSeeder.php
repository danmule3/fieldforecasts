<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleDemoSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::role(User::ROLE_SUPER_ADMIN)->first() ?? User::first();
        $category = Category::where('slug', 'match-analysis')->first();
        $tags = Tag::whereIn('slug', ['analysis', 'weekend-preview'])->pluck('id');

        if (! $author) {
            $this->command?->warn('No user found to author demo articles — run AdminUserSeeder first.');
            return;
        }

        $article = Article::firstOrCreate(
            ['slug' => 'how-we-build-our-predictions'],
            [
                'category_id' => $category?->id,
                'author_id' => $author->id,
                'title' => 'How we build our predictions',
                'excerpt' => 'A look at the data and process behind every Field Forecast prediction.',
                'body' => "Every prediction on Field Forecast starts with recent form, head-to-head history, and squad news.\n\nOur editors combine statistical models with match-specific context — injuries, fixture congestion, and tactical trends — before publishing a confidence rating.\n\nWe track every prediction's outcome publicly so our accuracy stays transparent.",
                'status' => Article::STATUS_PUBLISHED,
                'published_at' => now()->subDays(2),
                'meta_title' => 'How Field Forecast builds football predictions',
                'meta_description' => 'A behind-the-scenes look at our prediction process, data sources, and accuracy tracking.',
            ]
        );

        $article->tags()->sync($tags);
    }
}
