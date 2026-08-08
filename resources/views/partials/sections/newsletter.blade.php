<section class="py-12">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-xl font-bold mb-2">{{ $section->title ?? 'Stay in the loop' }}</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">{{ $section->description ?? "Get the week's top predictions and articles in your inbox." }}</p>

        @session('status')
            @if (session('status') === 'newsletter-subscribed')
                <x-alert type="success" class="mb-4">Thanks for subscribing!</x-alert>
            @endif
        @endsession

        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex gap-2">
            @csrf
            <input type="email" name="email" required placeholder="you@example.com"
                   class="flex-1 rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
            <x-button type="submit">Subscribe</x-button>
        </form>
    </div>
</section>
