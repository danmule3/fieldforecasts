<?php $items = $section->content['items'] ?? []; ?>

<?php if(!empty($items)): ?>
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if($section->title): ?>
                <h2 class="text-2xl font-bold text-center mb-2"><?php echo e($section->title); ?></h2>
            <?php endif; ?>
            <?php if($section->description): ?>
                <p class="text-slate-500 dark:text-slate-400 text-center mb-8 max-w-2xl mx-auto"><?php echo e($section->description); ?></p>
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-center">
                        <?php if(!empty($item['icon'])): ?>
                            <div class="text-3xl mb-2"><?php echo e($item['icon']); ?></div>
                        <?php endif; ?>
                        <h3 class="font-semibold mb-1"><?php echo e($item['title'] ?? ''); ?></h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400"><?php echo e($item['text'] ?? ''); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/partials/sections/features.blade.php ENDPATH**/ ?>