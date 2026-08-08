<?php if (isset($component)) { $__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.match-section','data' => ['title' => $section->title ?? 'Upcoming','matches' => $sections['upcoming'],'emptyText' => 'No upcoming matches scheduled.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('match-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($section->title ?? 'Upcoming'),'matches' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sections['upcoming']),'empty-text' => 'No upcoming matches scheduled.']); ?>
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
<?php /**PATH C:\Sites\fieldforecasts\resources\views/partials/sections/upcoming_matches.blade.php ENDPATH**/ ?>