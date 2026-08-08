<?php

namespace App\Http\Controllers\Admin;

use App\Models\PageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageSectionController extends AdminController
{
    public function index(): View
    {
        $sections = PageSection::forPage('home')->ordered()->get();

        return view('admin.page-sections.index', [
            'sections' => $sections,
            'totalCount' => $sections->count(),
            'activeCount' => $sections->where('is_visible', true)->count(),
            'hiddenCount' => $sections->where('is_visible', false)->count(),
        ]);
    }

    public function edit(PageSection $pageSection): View
    {
        return view('admin.page-sections.edit', ['section' => $pageSection]);
    }

    public function update(Request $request, PageSection $pageSection): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'headline' => ['nullable', 'string', 'max:200'],
            'subheadline' => ['nullable', 'string', 'max:300'],
            // One "label|value" or "icon|title|text" pair per line —
            // simple textarea input instead of a JS repeater, which
            // keeps this reliable without adding new frontend tooling.
            'items_raw' => ['nullable', 'string'],
        ]);

        $update = [
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
        ];

        $content = $pageSection->content ?? [];

        if ($pageSection->type === 'hero') {
            $content['headline'] = $data['headline'] ?? '';
            $content['subheadline'] = $data['subheadline'] ?? '';
        }

        if ($pageSection->type === 'stats') {
            $content['items'] = $this->parseLines($data['items_raw'] ?? '', ['label', 'value']);
        }

        if ($pageSection->type === 'features') {
            $content['items'] = $this->parseLines($data['items_raw'] ?? '', ['icon', 'title', 'text']);
        }

        if (in_array($pageSection->type, ['hero', 'stats', 'features'], true)) {
            $update['content'] = $content;
        }

        $pageSection->update($update);

        return redirect()->route('admin.page-sections.index')->with('status', 'Section updated.');
    }

    public function toggleVisible(PageSection $pageSection): RedirectResponse
    {
        $pageSection->update(['is_visible' => ! $pageSection->is_visible]);

        return back()->with('status', $pageSection->is_visible ? 'Section shown.' : 'Section hidden.');
    }

    /** Swaps display_order with the section immediately above it. */
    public function moveUp(PageSection $pageSection): RedirectResponse
    {
        $above = PageSection::forPage($pageSection->page)
            ->where('display_order', '<', $pageSection->display_order)
            ->orderByDesc('display_order')
            ->first();

        $this->swapOrder($pageSection, $above);

        return back();
    }

    /** Swaps display_order with the section immediately below it. */
    public function moveDown(PageSection $pageSection): RedirectResponse
    {
        $below = PageSection::forPage($pageSection->page)
            ->where('display_order', '>', $pageSection->display_order)
            ->orderBy('display_order')
            ->first();

        $this->swapOrder($pageSection, $below);

        return back();
    }

    private function swapOrder(PageSection $a, ?PageSection $b): void
    {
        if (! $b) {
            return;
        }

        [$orderA, $orderB] = [$a->display_order, $b->display_order];
        $a->update(['display_order' => $orderB]);
        $b->update(['display_order' => $orderA]);
    }

    /** @return array<int, array<string, string>> */
    private function parseLines(string $raw, array $fields): array
    {
        return collect(explode("\n", $raw))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) use ($fields) {
                $parts = array_map('trim', explode('|', $line));

                return collect($fields)->mapWithKeys(fn ($field, $i) => [$field => $parts[$i] ?? ''])->all();
            })
            ->values()
            ->all();
    }
}
