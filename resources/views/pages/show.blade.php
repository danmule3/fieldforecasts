<x-layouts.app :title="$page->meta_title ?? $page->title" :description="$page->meta_description">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 prose dark:prose-invert max-w-none">
        <h1>{{ $page->title }}</h1>
        {!! nl2br(e($page->body)) !!}
    </div>
</x-layouts.app>
