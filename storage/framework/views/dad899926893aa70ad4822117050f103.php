<?php
    $user = auth()->user();
    $isSuperAdmin = $user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN);
    $isAdminPlus = $user->hasAnyRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_SUPER_ADMIN]);

    $link = fn (string $route, string $label, string $icon = '•') => [
        'route' => $route, 'label' => $label, 'icon' => $icon, 'active' => request()->routeIs($route . '*'),
    ];

    $renderLink = function (array $item) {
        $active = $item['active'];
        $classes = $active
            ? 'bg-indigo-600 text-white font-medium shadow-sm'
            : 'text-slate-300 hover:bg-slate-800 hover:text-white';
        return '<a href="' . route($item['route']) . '" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition ' . $classes . '">'
            . '<span class="text-base leading-none">' . $item['icon'] . '</span>'
            . '<span>' . e($item['label']) . '</span>'
            . '</a>';
    };
?>

<div class="p-4 flex flex-col h-full bg-slate-900 text-slate-300">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2 mb-6 px-2">
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Field Forecast" class="h-7 w-auto">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Admin</span>
    </a>

    <nav class="flex-1 space-y-1 overflow-y-auto">
        <p class="px-2 text-xs font-semibold text-slate-500 uppercase mt-2 mb-1">Overview</p>
        <?php echo $renderLink($link('admin.dashboard', 'Dashboard', '🏠')); ?>


        <p class="px-2 text-xs font-semibold text-slate-500 uppercase mt-4 mb-1">Content</p>
        <?php $__currentLoopData = [
            $link('admin.predictions.index', 'Predictions', '🎯'),
            $link('admin.matches.index', 'Matches', '🏆'),
            $link('admin.leagues.index', 'Leagues', '📋'),
            $link('admin.teams.index', 'Teams', '👥'),
            $link('admin.sports.index', 'Sports', '⚽'),
            $link('admin.countries.index', 'Countries', '🌍'),
            $link('admin.markets.index', 'Markets', '📊'),
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $renderLink($item); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <p class="px-2 text-xs font-semibold text-slate-500 uppercase mt-4 mb-1">Blog &amp; CMS</p>
        <?php $__currentLoopData = [
            $link('admin.articles.index', 'Articles', '📝'),
            $link('admin.comments.index', 'Comments', '💬'),
            $link('admin.categories.index', 'Categories', '🏷️'),
            $link('admin.tags.index', 'Tags', '🔖'),
            $link('admin.faqs.index', 'FAQ', '❓'),
            $link('admin.testimonials.index', 'Testimonials', '⭐'),
            $link('admin.advertisements.index', 'Advertisements', '📣'),
            $link('admin.pages.index', 'Pages', '📄'),
            $link('admin.page-sections.index', 'Homepage sections', '🧩'),
            $link('admin.menus.index', 'Menus', '📑'),
            $link('admin.slides.index', 'Slides & banners', '🖼️'),
            $link('admin.newsletter.index', 'Newsletter', '✉️'),
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $renderLink($item); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($isAdminPlus): ?>
            <p class="px-2 text-xs font-semibold text-slate-500 uppercase mt-4 mb-1">People &amp; billing</p>
            <?php $__currentLoopData = [
                $link('admin.users.index', 'Users', '👤'),
                $link('admin.subscription-plans.index', 'Subscription plans', '💳'),
                $link('admin.payments.index', 'Payments', '💰'),
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $renderLink($item); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <p class="px-2 text-xs font-semibold text-slate-500 uppercase mt-4 mb-1">System</p>
            <?php $__currentLoopData = [
                $link('admin.logs.index', 'Activity logs', '🕒'),
                $link('admin.settings.edit', 'Settings', '⚙️'),
                $link('admin.api-keys.index', 'API keys', '🔑'),
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $renderLink($item); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if($isSuperAdmin): ?>
            <?php echo $renderLink($link('admin.roles.index', 'Roles & permissions', '🛡️')); ?>

        <?php endif; ?>
    </nav>

    <div class="border-t border-slate-800 pt-3 mt-3 text-sm">
        <p class="px-2 text-slate-400 truncate"><?php echo e($user->name); ?></p>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button class="w-full text-left rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white">Log out</button>
        </form>
    </div>
</div>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/components/admin/sidebar.blade.php ENDPATH**/ ?>