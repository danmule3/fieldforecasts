<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\GameMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('<urlset', false);
        $response->assertSee(route('home'), false);
    }

    public function test_robots_txt_references_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertSee('Sitemap:');
        $response->assertSee('Disallow: /admin');
    }

    public function test_llms_txt_is_reachable(): void
    {
        $this->get('/llms.txt')->assertOk()->assertSee('Field Forecast');
    }

    public function test_homepage_includes_organization_schema(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type":"Organization"', false);
    }

    public function test_match_page_includes_sports_event_schema(): void
    {
        $match = GameMatch::factory()->create();

        $response = $this->get(route('matches.show', $match));

        $response->assertSee('"@type":"SportsEvent"', false);
        $response->assertSee($match->homeTeam->name, false);
    }

    public function test_article_page_includes_article_schema_and_canonical(): void
    {
        $article = Article::factory()->create();

        $response = $this->get(route('articles.show', $article));

        $response->assertSee('"@type":"Article"', false);
        $response->assertSee('rel="canonical"', false);
    }

    public function test_auth_pages_are_noindex(): void
    {
        $this->get('/login')->assertSee('noindex', false);
    }

    public function test_admin_area_is_disallowed_in_robots(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertSee('/admin');
    }
}
