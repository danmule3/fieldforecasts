<x-layouts.app :title="__('Football & Sports Predictions')" :description="__('Expert match predictions, odds, statistics and analysis across football, basketball, tennis and more.')">
    @foreach ($pageSections as $section)
        @includeIf('partials.sections.' . $section->type, ['section' => $section])
    @endforeach
</x-layouts.app>
