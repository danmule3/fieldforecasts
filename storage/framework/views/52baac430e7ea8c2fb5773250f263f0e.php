<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'noindex' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'noindex' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $siteName = 'Field Forecast';
    $fullTitle = $title ? "{$title} | {$siteName}" : "{$siteName} — Football & Sports Predictions";
    $metaDescription = $description ?? 'Football and sports match predictions, odds, statistics, and expert analysis. Informational only — Field Forecast does not accept wagers.';
    $canonical = url()->current();
    $ogImage = $image ? (str_starts_with($image, 'http') ? $image : Storage::url($image)) : asset('images/logo.png');
?>

<title><?php echo e($fullTitle); ?></title>
<meta name="description" content="<?php echo e(Str::limit($metaDescription, 160)); ?>">
<link rel="canonical" href="<?php echo e($canonical); ?>">

<?php if($noindex): ?>
    <meta name="robots" content="noindex, nofollow">
<?php else: ?>
    <meta name="robots" content="index, follow">
<?php endif; ?>


<meta property="og:site_name" content="<?php echo e($siteName); ?>">
<meta property="og:title" content="<?php echo e($fullTitle); ?>">
<meta property="og:description" content="<?php echo e(Str::limit($metaDescription, 200)); ?>">
<meta property="og:type" content="<?php echo e($type); ?>">
<meta property="og:url" content="<?php echo e($canonical); ?>">
<meta property="og:image" content="<?php echo e($ogImage); ?>">


<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo e($fullTitle); ?>">
<meta name="twitter:description" content="<?php echo e(Str::limit($metaDescription, 200)); ?>">
<meta name="twitter:image" content="<?php echo e($ogImage); ?>">
<?php /**PATH C:\Sites\fieldforecasts\resources\views/components/seo-meta.blade.php ENDPATH**/ ?>