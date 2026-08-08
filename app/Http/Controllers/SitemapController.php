<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\GameMatch;
use App\Models\League;
use App\Models\Page;
use App\Models\Sport;
use App\Models\Team;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Cached for an hour — this touches several tables and doesn't
     * need to be byte-perfect real-time; search engines re-crawl on
     * their own schedule regardless.
     */
    public function index(): Response
    {
        $xml = Cache::remember('sitemap:xml', now()->addHour(), function () {
            $urls = collect();

            $urls->push(['loc' => route('home'), 'priority' => '1.0']);
            $urls->push(['loc' => route('sports.index'), 'priority' => '0.8']);
            $urls->push(['loc' => route('matches.index'), 'priority' => '0.8']);
            $urls->push(['loc' => route('predictions.index'), 'priority' => '0.8']);
            $urls->push(['loc' => route('articles.index'), 'priority' => '0.6']);
            $urls->push(['loc' => route('faq.index'), 'priority' => '0.4']);

            foreach (Sport::where('is_active', true)->get() as $sport) {
                $urls->push(['loc' => route('sports.show', $sport), 'priority' => '0.7']);
            }

            foreach (League::where('is_active', true)->get() as $league) {
                $urls->push(['loc' => route('leagues.show', $league), 'priority' => '0.6']);
            }

            foreach (Team::limit(500)->get() as $team) {
                $urls->push(['loc' => route('teams.show', $team), 'priority' => '0.5']);
            }

            // Only forward-looking + very recent matches — old finished
            // fixtures add little SEO value and bloat the sitemap.
            foreach (GameMatch::where('kickoff_at', '>=', now()->subDays(3))->limit(1000)->get() as $match) {
                $urls->push(['loc' => route('matches.show', $match), 'lastmod' => $match->updated_at->toAtomString(), 'priority' => '0.5']);
            }

            foreach (Article::published()->limit(500)->get() as $article) {
                $urls->push(['loc' => route('articles.show', $article), 'lastmod' => $article->updated_at->toAtomString(), 'priority' => '0.6']);
            }

            foreach (Page::active()->get() as $page) {
                $urls->push(['loc' => route('pages.show', $page), 'priority' => '0.3']);
            }

            return view('sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
