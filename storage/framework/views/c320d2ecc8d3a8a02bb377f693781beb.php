<?php if (isset($component)) { $__componentOriginalc8c9fd5d7827a77a31381de67195f0c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8c9fd5d7827a77a31381de67195f0c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.admin','data' => ['title' => 'Dashboard']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Dashboard')]); ?>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <?php $__currentLoopData = [
            ['label' => 'Registered users', 'value' => number_format($stats['registered_users']), 'icon' => '👤', 'color' => 'bg-indigo-600'],
            ['label' => 'Premium users', 'value' => number_format($stats['premium_users']), 'icon' => '⭐', 'color' => 'bg-amber-500'],
            ['label' => 'Active subscriptions', 'value' => number_format($stats['active_subscriptions']), 'icon' => '💳', 'color' => 'bg-emerald-600'],
            ['label' => 'Revenue', 'value' => number_format($stats['revenue_cents'] / 100, 2) . ' USD', 'icon' => '💰', 'color' => 'bg-purple-600'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
                <div class="w-9 h-9 rounded-lg <?php echo e($card['color']); ?> text-white flex items-center justify-center text-base mb-3"><?php echo e($card['icon']); ?></div>
                <p class="text-xs text-slate-500 dark:text-slate-400"><?php echo e($card['label']); ?></p>
                <p class="text-2xl font-bold mt-1"><?php echo e($card['value']); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Predictions</p>
            <p class="text-lg font-semibold"><?php echo e($stats['total_predictions']); ?> total &middot; <?php echo e($stats['settled_predictions']); ?> settled</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Subscription growth</p>
            <p class="text-lg font-semibold">
                <?php echo e($stats['subscriptions_this_month']); ?> this month
                <span class="text-sm text-slate-400">(vs <?php echo e($stats['subscriptions_last_month']); ?> last month)</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
            <h2 class="font-semibold mb-3">Most viewed predictions</h2>
            <ul class="text-sm divide-y divide-slate-100 dark:divide-slate-800">
                <?php $__empty_1 = true; $__currentLoopData = $mostViewed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prediction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="py-2 flex justify-between">
                        <a href="<?php echo e(route('predictions.show', $prediction)); ?>" class="hover:underline truncate">
                            <?php echo e($prediction->match->homeTeam->name); ?> vs <?php echo e($prediction->match->awayTeam->name); ?>

                        </a>
                        <span class="text-slate-400"><?php echo e($prediction->views_count); ?> views</span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="py-2 text-slate-400">No views recorded yet.</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
            <h2 class="font-semibold mb-3">Popular sports</h2>
            <ul class="text-sm divide-y divide-slate-100 dark:divide-slate-800">
                <?php $__currentLoopData = $popularSports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="py-2 flex justify-between">
                        <span><?php echo e($sport->name); ?></span>
                        <span class="text-slate-400"><?php echo e($sport->matches_count); ?> matches</span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Recent activity</h2>
            <a href="<?php echo e(route('admin.logs.index')); ?>" class="text-sm text-indigo-600 dark:text-indigo-400">View all &rarr;</a>
        </div>
        <ul class="text-sm divide-y divide-slate-100 dark:divide-slate-800">
            <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="py-2 flex justify-between">
                    <span><?php echo e($log->user?->name ?? 'System'); ?> &mdash; <?php echo e($log->event); ?></span>
                    <span class="text-slate-400"><?php echo e($log->created_at->diffForHumans()); ?></span>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="py-2 text-slate-400">No activity recorded yet.</li>
            <?php endif; ?>
        </ul>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8c9fd5d7827a77a31381de67195f0c3)): ?>
<?php $attributes = $__attributesOriginalc8c9fd5d7827a77a31381de67195f0c3; ?>
<?php unset($__attributesOriginalc8c9fd5d7827a77a31381de67195f0c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8c9fd5d7827a77a31381de67195f0c3)): ?>
<?php $component = $__componentOriginalc8c9fd5d7827a77a31381de67195f0c3; ?>
<?php unset($__componentOriginalc8c9fd5d7827a77a31381de67195f0c3); ?>
<?php endif; ?>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>