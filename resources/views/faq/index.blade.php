<x-layouts.app :title="__('FAQ')" :description="__('Frequently asked questions about Field Forecast predictions and subscriptions.')">
    <x-slot:schema>
        <x-schema.faq-page :faqs="$faqsByCategory->flatten()" />
    </x-slot:schema>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-8">Frequently asked questions</h1>

        @forelse ($faqsByCategory as $category => $faqs)
            <div class="mb-8">
                <h2 class="font-semibold mb-3">{{ $category }}</h2>
                <div class="space-y-2" x-data="{ open: null }">
                    @foreach ($faqs as $faq)
                        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4">
                            <button @click="open = open === {{ $faq->id }} ? null : {{ $faq->id }}" class="w-full text-left font-medium text-sm flex justify-between items-center">
                                {{ $faq->question }}
                                <span x-text="open === {{ $faq->id }} ? '−' : '+'"></span>
                            </button>
                            <p x-show="open === {{ $faq->id }}" x-cloak class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $faq->answer }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-500 dark:text-slate-400">No FAQs published yet.</p>
        @endforelse
    </div>
</x-layouts.app>
