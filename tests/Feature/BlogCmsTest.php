<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_article_is_visible(): void
    {
        $article = Article::factory()->create();

        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee($article->title);
    }

    public function test_draft_article_returns_404(): void
    {
        $article = Article::factory()->draft()->create();

        $this->get(route('articles.show', $article))->assertNotFound();
    }

    public function test_article_view_count_increments(): void
    {
        $article = Article::factory()->create(['views_count' => 0]);

        $this->get(route('articles.show', $article));

        $this->assertSame(1, $article->fresh()->views_count);
    }

    public function test_guest_cannot_comment(): void
    {
        $article = Article::factory()->create();

        $this->post(route('comments.store', $article), ['body' => 'Nice article'])
            ->assertRedirect('/login');
    }

    public function test_registered_user_comment_is_pending_by_default(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $this->actingAs($user)->post(route('comments.store', $article), ['body' => 'Great read!']);

        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'user_id' => $user->id,
            'status' => Comment::STATUS_PENDING,
        ]);
    }

    public function test_staff_comment_is_auto_approved(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole(User::ROLE_EDITOR);
        $article = Article::factory()->create();

        $this->actingAs($editor)->post(route('comments.store', $article), ['body' => 'Nice piece.']);

        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'user_id' => $editor->id,
            'status' => Comment::STATUS_APPROVED,
        ]);
    }

    public function test_active_page_renders(): void
    {
        $page = Page::factory()->create(['is_active' => true]);

        $this->get(route('pages.show', $page))->assertOk();
    }

    public function test_inactive_page_returns_404(): void
    {
        $page = Page::factory()->create(['is_active' => false]);

        $this->get(route('pages.show', $page))->assertNotFound();
    }

    public function test_newsletter_subscribe_creates_subscriber(): void
    {
        $this->post(route('newsletter.subscribe'), ['email' => 'reader@example.com'])
            ->assertRedirect();

        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'reader@example.com']);
    }

    public function test_newsletter_unsubscribe_via_token(): void
    {
        $this->post(route('newsletter.subscribe'), ['email' => 'reader2@example.com']);
        $subscriber = \App\Models\NewsletterSubscriber::where('email', 'reader2@example.com')->first();

        $this->get(route('newsletter.unsubscribe', $subscriber->token))->assertOk();

        $this->assertNotNull($subscriber->fresh()->unsubscribed_at);
    }

    public function test_admin_can_create_article(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole(User::ROLE_EDITOR);

        $response = $this->actingAs($editor)->post(route('admin.articles.store'), [
            'title' => 'Weekend Premier League Preview',
            'excerpt' => 'A look at this weekend\'s fixtures.',
            'body' => 'Full article body here.',
            'status' => 'published',
            'tags' => 'premier-league, weekend-preview',
        ]);

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseHas('articles', ['title' => 'Weekend Premier League Preview']);
    }

    public function test_admin_can_moderate_comments(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(User::ROLE_ADMIN);

        $article = Article::factory()->create();
        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => User::factory()->create()->id,
            'body' => 'Spammy content',
            'status' => Comment::STATUS_PENDING,
        ]);

        $this->actingAs($admin)->patch(route('admin.comments.approve', $comment))->assertRedirect();
        $this->assertSame(Comment::STATUS_APPROVED, $comment->fresh()->status);
    }
}
