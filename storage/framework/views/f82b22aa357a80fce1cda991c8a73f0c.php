<nav x-data="{ mobileOpen: false }" class="sticky top-0 z-40 bg-white/80 dark:bg-slate-950/80 backdrop-blur border-b border-slate-200 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="<?php echo e(route('home')); ?>" class="flex items-center">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Field Forecast" class="h-9 w-auto">
            </a>

            
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="<?php echo e(route('sports.index')); ?>" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Sports</a>
                <a href="<?php echo e(route('matches.index')); ?>" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Matches</a>
                <a href="<?php echo e(route('predictions.index')); ?>" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Predictions</a>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Premium</a>
                <a href="<?php echo e(route('articles.index')); ?>" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Blog</a>

                <button @click="dark = !dark" type="button" class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300" aria-label="Toggle dark mode">
                    <span x-show="!dark">🌙</span>
                    <span x-show="dark">☀️</span>
                </button>

                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Dashboard</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'secondary']); ?>Log out <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Log in</a>
                    <a href="<?php echo e(route('register')); ?>">
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary']); ?>Sign up <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                    </a>
                <?php endif; ?>
            </div>

            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2" aria-label="Toggle menu">
                <span class="block w-6 h-0.5 bg-slate-700 dark:bg-slate-200 mb-1"></span>
                <span class="block w-6 h-0.5 bg-slate-700 dark:bg-slate-200 mb-1"></span>
                <span class="block w-6 h-0.5 bg-slate-700 dark:bg-slate-200"></span>
            </button>
        </div>

        <div x-show="mobileOpen" x-cloak class="md:hidden pb-4 flex flex-col gap-3 text-sm font-medium">
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-left">Log out</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>">Log in</a>
                <a href="<?php echo e(route('register')); ?>">Sign up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/components/navigation.blade.php ENDPATH**/ ?>