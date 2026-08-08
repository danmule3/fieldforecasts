<x-layouts.guest :title="__('Newsletter')">
    <div class="text-center">
        @if ($success)
            <h1 class="text-lg font-semibold mb-2">You've been unsubscribed</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">You won't receive any more newsletter emails from us.</p>
        @else
            <h1 class="text-lg font-semibold mb-2">Link not found</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">This unsubscribe link is invalid or has already been used.</p>
        @endif
        <a href="{{ route('home') }}" class="text-indigo-600 dark:text-indigo-400 text-sm mt-4 inline-block">Back to Field Forecast</a>
    </div>
</x-layouts.guest>
