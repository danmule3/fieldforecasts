<?php if (isset($component)) { $__componentOriginal73f67aaac8cfbebcdf29e003b3670e2d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal73f67aaac8cfbebcdf29e003b3670e2d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.match-section','data' => ['title' => $section->title ?? 'Featured','matches' => $sections['featured'],'emptyText' => 'No featured matches right now.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('match-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($section->title ?? 'Featured'),'matches' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sections['featured']),'empty-text' => 'No featured matches right now.']); ?>
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
<?php /**PATH C:\Sites\fieldforecasts\resources\views/partials/sections/featured_matches.blade.php ENDPATH**/ ?>