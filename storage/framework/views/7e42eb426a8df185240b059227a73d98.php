<footer class="border-t border-slate-200 dark:border-slate-800 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 text-sm text-slate-500 dark:text-slate-400">
        <div class="flex flex-wrap gap-x-6 gap-y-2 mb-6">
            <a href="<?php echo e(route('articles.index')); ?>" class="hover:underline">Blog</a>
            <a href="<?php echo e(route('faq.index')); ?>" class="hover:underline">FAQ</a>
            <?php $__currentLoopData = \App\Models\Page::active()->get(['title', 'slug']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('pages.show', $page)); ?>" class="hover:underline"><?php echo e($page->title); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <p>&copy; <?php echo e(now()->year); ?> Field Forecast. All predictions are informational only.</p>
            <p class="max-w-md">
                Field Forecast publishes statistics, analysis, and predictions for informational purposes only.
                We do not accept wagers or facilitate betting of any kind.
            </p>
        </div>
    </div>
</footer>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/components/footer.blade.php ENDPATH**/ ?>