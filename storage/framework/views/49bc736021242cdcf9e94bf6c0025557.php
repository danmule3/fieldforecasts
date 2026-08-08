<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['prediction', 'canView' => null]));

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

foreach (array_filter((['prediction', 'canView' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $canView = $canView ?? (auth()->user()?->can('view', $prediction) ?? ! $prediction->is_premium);
?>

<div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4 relative overflow-hidden">
    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-2">
        <span><?php echo e($prediction->match->league->name ?? ''); ?></span>
        <?php if($prediction->is_premium): ?>
            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 px-2 py-0.5 font-semibold">★ Premium</span>
        <?php endif; ?>
    </div>

    <a href="<?php echo e(route('predictions.show', $prediction)); ?>" class="font-semibold hover:underline">
        <?php echo e($prediction->match->homeTeam->name); ?> vs <?php echo e($prediction->match->awayTeam->name); ?>

    </a>

    <div class="mt-3 flex items-center justify-between text-sm">
        <span class="text-slate-500 dark:text-slate-400"><?php echo e($prediction->market->name ?? ''); ?></span>
        <span class="font-bold"><?php echo e($prediction->confidence); ?>% confidence</span>
    </div>

    <div class="mt-3 relative">
        <p class="text-sm text-slate-600 dark:text-slate-300 <?php echo e($canView ? '' : 'blur-sm select-none'); ?>">
            <?php echo e(Str::limit($prediction->analysis, 140)); ?>

        </p>

        <?php if (! ($canView)): ?>
            <div class="absolute inset-0 flex items-center justify-center">
                <a href="<?php echo e(route('predictions.show', $prediction)); ?>" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-white/90 dark:bg-slate-900/90 rounded-full px-3 py-1">
                    Unlock with Premium
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if($prediction->status !== \App\Models\Prediction::STATUS_PENDING): ?>
        <div class="mt-3">
            <?php
                $statusStyles = [
                    'won' => 'text-emerald-600 dark:text-emerald-400',
                    'lost' => 'text-red-600 dark:text-red-400',
                    'cancelled' => 'text-slate-400',
                ];
            ?>
            <span class="text-xs font-semibold <?php echo e($statusStyles[$prediction->status]); ?>"><?php echo e(ucfirst($prediction->status)); ?></span>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/components/prediction-card.blade.php ENDPATH**/ ?>