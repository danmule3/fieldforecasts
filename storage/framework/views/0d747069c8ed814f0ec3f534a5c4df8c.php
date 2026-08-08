<?php if($testimonials->isNotEmpty()): ?>
    <section class="py-8 bg-slate-100/60 dark:bg-slate-900/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold mb-6"><?php echo e($section->title ?? 'What our users say'); ?></h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-3">&ldquo;<?php echo e($testimonial->quote); ?>&rdquo;</p>
                        <p class="text-sm font-medium"><?php echo e($testimonial->name); ?></p>
                        <?php if($testimonial->role): ?>
                            <p class="text-xs text-slate-400"><?php echo e($testimonial->role); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/partials/sections/testimonials.blade.php ENDPATH**/ ?>