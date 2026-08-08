@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'noindex' => false,
])

@php
    $siteName = 'Field Forecast';
    $fullTitle = $title ? "{$title} | {$siteName}" : "{$siteName} — Football & Sports Predictions";
    $metaDescription = $description ?? 'Football and sports match predictions, odds, statistics, and expert analysis. Informational only — Field Forecast does not accept wagers.';
    $canonical = url()->current();
    $ogImage = $image ? (str_starts_with($image, 'http') ? $image : Storage::url($image)) : asset('images/logo.png');
@endphp

<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ Str::limit($metaDescription, 160) }}">
<link rel="canonical" href="{{ $canonical }}">

@if ($noindex)
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="robots" content="index, follow">
@endif

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ Str::limit($metaDescription, 200) }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ Str::limit($metaDescription, 200) }}">
<meta name="twitter:image" content="{{ $ogImage }}">
