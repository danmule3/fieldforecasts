@props(['article'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $article->title,
        'description' => $article->excerpt,
        'datePublished' => $article->published_at?->toIso8601String(),
        'dateModified' => $article->updated_at->toIso8601String(),
        'author' => ['@type' => 'Person', 'name' => $article->author->name],
        'image' => $article->featured_image_path ? Storage::url($article->featured_image_path) : null,
        'mainEntityOfPage' => route('articles.show', $article),
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Field Forecast',
        ],
    ];

    $schema = array_filter($schema, fn ($v) => $v !== null);
@endphp

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES) !!}</script>
