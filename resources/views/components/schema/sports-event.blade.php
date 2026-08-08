@props(['match'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'SportsEvent',
        'name' => $match->homeTeam->name . ' vs ' . $match->awayTeam->name,
        'startDate' => $match->kickoff_at->toIso8601String(),
        'eventStatus' => match ($match->status) {
            'finished' => 'https://schema.org/EventCompleted',
            'postponed' => 'https://schema.org/EventPostponed',
            'cancelled' => 'https://schema.org/EventCancelled',
            default => 'https://schema.org/EventScheduled',
        },
        'location' => $match->venue ? [
            '@type' => 'Place',
            'name' => $match->venue,
        ] : null,
        'competitor' => [
            ['@type' => 'SportsTeam', 'name' => $match->homeTeam->name],
            ['@type' => 'SportsTeam', 'name' => $match->awayTeam->name],
        ],
        'homeTeam' => ['@type' => 'SportsTeam', 'name' => $match->homeTeam->name],
        'awayTeam' => ['@type' => 'SportsTeam', 'name' => $match->awayTeam->name],
        'sport' => $match->sport->name,
        'url' => route('matches.show', $match),
    ];

    // Drop null values (e.g. no venue) — schema.org validators flag null/empty fields.
    $schema = array_filter($schema, fn ($v) => $v !== null);
@endphp

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES) !!}</script>
