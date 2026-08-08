<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['match']));

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

foreach (array_filter((['match']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e(route('matches.show', $match)); ?>"
   class="block bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4 hover:ring-indigo-500 transition">
    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-3">
        <span><?php echo e($match->league->name); ?></span>
        <?php if($match->isLive()): ?>
            <span class="inline-flex items-center gap-1 text-red-600 dark:text-red-400 font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span>
                LIVE <?php if($match->minute): ?> <?php echo e($match->minute); ?>' <?php endif; ?>
            </span>
        <?php else: ?>
            <span><?php echo e($match->kickoff_at->format('D, H:i')); ?></span>
        <?php endif; ?>
    </div>

    <div class="flex items-center justify-between text-sm font-medium">
        <span class="truncate"><?php echo e($match->homeTeam->short_name ?? $match->homeTeam->name); ?></span>
        <?php if($match->status === \App\Models\GameMatch::STATUS_FINISHED || $match->isLive()): ?>
            <span class="font-bold px-2"><?php echo e($match->home_score); ?> - <?php echo e($match->away_score); ?></span>
        <?php else: ?>
            <span class="text-slate-400 px-2">vs</span>
        <?php endif; ?>
        <span class="truncate text-right"><?php echo e($match->awayTeam->short_name ?? $match->awayTeam->name); ?></span>
    </div>
</a>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/components/match-card.blade.php ENDPATH**/ ?>