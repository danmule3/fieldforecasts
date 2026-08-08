<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold"><?php echo e($section->title ?? 'Latest articles'); ?></h2>
            <a href="<?php echo e(route('articles.index')); ?>" class="text-sm font-medium text-indigo-600 dark:text-indigo-400">View all &rarr;</a>
        </div>

        <?php if($latestArticles->isEmpty()): ?>
            <p class="text-sm text-slate-500 dark:text-slate-400">No articles published yet.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <?php $__currentLoopData = $latestArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('articles.show', $article)); ?>" class="block bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4 hover:ring-indigo-500 transition">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1"><?php echo e($article->category->name ?? 'General'); ?></p>
                        <p class="font-medium text-sm"><?php echo e($article->title); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/partials/sections/latest_articles.blade.php ENDPATH**/ ?>