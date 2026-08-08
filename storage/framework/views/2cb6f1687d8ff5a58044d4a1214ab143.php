<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'info']));

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

foreach (array_filter((['type' => 'info']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$styles = [
    'info' => 'bg-indigo-50 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-200 ring-indigo-600/20',
    'success' => 'bg-emerald-50 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-200 ring-emerald-600/20',
    'error' => 'bg-red-50 dark:bg-red-950 text-red-800 dark:text-red-200 ring-red-600/20',
];
?>

<div <?php echo e($attributes->merge(['class' => 'rounded-lg px-4 py-3 text-sm ring-1 ring-inset ' . $styles[$type]])); ?> role="alert">
    <?php echo e($slot); ?>

</div>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/components/alert.blade.php ENDPATH**/ ?>