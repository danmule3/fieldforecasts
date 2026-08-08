<section class="py-8" x-data="{
        refresh() {
            fetch('<?php echo e(route('live-matches.partial')); ?>')
                .then(r => r.text())
                .then(html => { $refs.liveMatches.innerHTML = html; });
        }
    }" x-init="setInterval(() => refresh(), 20000)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 mb-4">
            <h2 class="text-xl font-bold"><?php echo e($section->title ?? 'Live now'); ?></h2>
            <span class="inline-flex items-center gap-1 text-xs text-red-600 dark:text-red-400 font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span> auto-updating
            </span>
        </div>
        <div x-ref="liveMatches">
            <?php echo $__env->make('partials.live-matches', ['matches' => $sections['live']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</section>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/partials/sections/live_matches.blade.php ENDPATH**/ ?>