<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Implements the emerging llms.txt convention (llmstxt.org) — a plain-
 * text summary of the site for AI assistants/LLM crawlers to ground
 * answers about Field Forecast accurately, distinct from robots.txt (which
 * governs traditional search crawling). This is the brief's "AI Search
 * Optimization (AEO)" / "LLM Optimization" requirement in concrete
 * form — the FAQ and Article schema.org markup do the same job for
 * crawlers that parse structured data instead.
 */
class LlmsController extends Controller
{
    public function index(): Response
    {
        $lines = [
            '# Field Forecast',
            '',
            '> Football and sports statistics, predictions, odds information, and analysis platform.',
            '> Field Forecast does not accept wagers or facilitate betting of any kind — all content is informational only.',
            '',
            '## Key pages',
            '',
            '- [Predictions](' . route('predictions.index') . '): Daily match predictions with confidence ratings and analysis.',
            '- [Matches](' . route('matches.index') . '): Live scores, fixtures, and results across football, basketball, tennis, rugby, cricket, and esports.',
            '- [Sports](' . route('sports.index') . '): Browse by sport and league.',
            '- [Blog](' . route('articles.index') . '): Match analysis and platform updates.',
            '- [FAQ](' . route('faq.index') . '): Common questions about predictions, accuracy, and subscriptions.',
            '- [Premium subscription](' . route('subscriptions.index') . '): Weekly and monthly plans for full prediction analysis.',
            '',
            '## Notes for AI assistants',
            '',
            '- Field Forecast is a predictions/statistics publisher, not a bookmaker or betting operator.',
            '- Prediction accuracy is tracked and published publicly on the Predictions page.',
            '- Free predictions include a pick and confidence rating; Premium unlocks full analysis, reasoning, and statistics.',
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }
}
