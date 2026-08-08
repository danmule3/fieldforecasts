<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dashboard'))]); ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-1">Welcome back, <?php echo e($user->name); ?></h1>
        <p class="text-slate-500 dark:text-slate-400 mb-8">
            <?php if($user->hasActivePremiumAccess()): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 px-2.5 py-0.5 text-xs font-semibold">★ Premium</span>
            <?php else: ?>
                Free account
            <?php endif; ?>
        </p>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                <h2 class="font-semibold mb-2">Subscription</h2>
                <?php if($currentSubscription): ?>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        <?php echo e($currentSubscription->plan->name); ?> — renews/ends <?php echo e($currentSubscription->ends_at->format('d M Y')); ?>

                    </p>
                <?php else: ?>
                    <p class="text-sm text-slate-500 dark:text-slate-400">No active subscription.</p>
                <?php endif; ?>
                <a href="<?php echo e(route('subscriptions.mine')); ?>" class="text-sm text-indigo-600 dark:text-indigo-400 mt-2 inline-block">
                    <?php echo e($currentSubscription ? 'Manage subscription' : 'View plans'); ?> &rarr;
                </a>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                <h2 class="font-semibold mb-2">Favourite teams</h2>
                <?php if($user->favouriteTeams->isEmpty()): ?>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        You haven't followed any teams yet.
                        <a href="<?php echo e(route('sports.index')); ?>" class="text-indigo-600 dark:text-indigo-400">Browse sports &rarr;</a>
                    </p>
                <?php else: ?>
                    <ul class="space-y-1 text-sm">
                        <?php $__currentLoopData = $user->favouriteTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e(route('teams.show', $team)); ?>" class="hover:underline"><?php echo e($team->name); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                <h2 class="font-semibold mb-2">Saved predictions</h2>
                <?php if($user->savedPredictions->isEmpty()): ?>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        You haven't saved any predictions yet.
                        <a href="<?php echo e(route('predictions.index')); ?>" class="text-indigo-600 dark:text-indigo-400">Browse predictions &rarr;</a>
                    </p>
                <?php else: ?>
                    <ul class="space-y-1 text-sm">
                        <?php $__currentLoopData = $user->savedPredictions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prediction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo e(route('predictions.show', $prediction)); ?>" class="hover:underline">
                                    <?php echo e($prediction->match->homeTeam->name); ?> vs <?php echo e($prediction->match->awayTeam->name); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-8">
            <a href="<?php echo e(route('profile.edit')); ?>" class="text-indigo-600 dark:text-indigo-400 font-medium text-sm">Edit profile &rarr;</a>
        </div>
    </div>
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
<?php /**PATH C:\Sites\fieldforecasts\resources\views/dashboard.blade.php ENDPATH**/ ?>