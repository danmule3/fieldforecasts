<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => $sport->name,'description' => $sport->name . ' match predictions, odds and statistics.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sport->name),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sport->name . ' match predictions, odds and statistics.')]); ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-2"><?php echo e($sport->name); ?></h1>
        <p class="text-slate-500 dark:text-slate-400 mb-8">Predictions, odds, and statistics for <?php echo e($sport->name); ?>.</p>

        <div class="flex flex-wrap gap-2 mb-10">
            <?php $__currentLoopData = $leagues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $league): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('leagues.show', $league)); ?>"
                   class="rounded-full px-4 py-1.5 text-sm font-medium bg-white dark:bg-slate-900 ring-1 ring-slate-900/5 dark:ring-white/10 hover:ring-indigo-500">
                    <?php echo e($league->name); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.match-section','data' => ['title' => 'Live now','matches' => $sections['live'],'emptyText' => 'No live '.e($sport->name).' matches right now.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('match-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Live now','matches' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sections['live']),'empty-text' => 'No live '.e($sport->name).' matches right now.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d)): ?>
<?php $attributes = $__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d; ?>
<?php unset($__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d)): ?>
<?php $component = $__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d; ?>
<?php unset($__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.match-section','data' => ['title' => 'Today','matches' => $sections['today'],'emptyText' => 'No '.e($sport->name).' matches scheduled today.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('match-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Today','matches' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sections['today']),'empty-text' => 'No '.e($sport->name).' matches scheduled today.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d)): ?>
<?php $attributes = $__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d; ?>
<?php unset($__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d)): ?>
<?php $component = $__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d; ?>
<?php unset($__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.match-section','data' => ['title' => 'Upcoming','matches' => $sections['upcoming'],'emptyText' => 'No upcoming '.e($sport->name).' matches.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('match-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Upcoming','matches' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sections['upcoming']),'empty-text' => 'No upcoming '.e($sport->name).' matches.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d)): ?>
<?php $attributes = $__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d; ?>
<?php unset($__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d)): ?>
<?php $component = $__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d; ?>
<?php unset($__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/sports/show.blade.php ENDPATH**/ ?>