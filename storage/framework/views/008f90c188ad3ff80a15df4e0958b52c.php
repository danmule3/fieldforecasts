<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold"><?php echo e($section->title ?? "Today's predictions"); ?></h2>
            <a href="<?php echo e(route('predictions.index')); ?>" class="text-sm font-medium text-indigo-600 dark:text-indigo-400">View all &rarr;</a>
        </div>

        <?php if($todaysPredictions->isEmpty()): ?>
            <p class="text-sm text-slate-500 dark:text-slate-400">No predictions published for today yet.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $todaysPredictions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prediction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginald8ddd7bc500f0680bdf2585cad94bf6c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ddd7bc500f0680bdf2585cad94bf6c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.prediction-card','data' => ['prediction' => $prediction]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('prediction-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['prediction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prediction)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ddd7bc500f0680bdf2585cad94bf6c)): ?>
<?php $attributes = $__attributesOriginald8ddd7bc500f0680bdf2585cad94bf6c; ?>
<?php unset($__attributesOriginald8ddd7bc500f0680bdf2585cad94bf6c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ddd7bc500f0680bdf2585cad94bf6c)): ?>
<?php $component = $__componentOriginald8ddd7bc500f0680bdf2585cad94bf6c; ?>
<?php unset($__componentOriginald8ddd7bc500f0680bdf2585cad94bf6c); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/partials/sections/todays_predictions.blade.php ENDPATH**/ ?>