<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slide;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SlideController extends AdminController
{
    public function __construct(private readonly ImageOptimizer $imageOptimizer)
    {
    }

    public function index(): View
    {
        return view('admin.slides.index', ['slides' => Slide::orderBy('placement')->orderBy('display_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.slides.form', ['slide' => new Slide()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image_path'] = $this->imageOptimizer->storeOptimized($request->file('image'), 'slides', 'public');
        unset($data['image']);

        Slide::create($data);

        return redirect()->route('admin.slides.index')->with('status', 'Slide created.');
    }

    public function edit(Slide $slide): View
    {
        return view('admin.slides.form', ['slide' => $slide]);
    }

    public function update(Request $request, Slide $slide): RedirectResponse
    {
        $data = $this->validated($request, required: false);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slide->image_path);
            $data['image_path'] = $this->imageOptimizer->storeOptimized($request->file('image'), 'slides', 'public');
        }
        unset($data['image']);

        $slide->update($data);

        return redirect()->route('admin.slides.index')->with('status', 'Slide updated.');
    }

    public function destroy(Slide $slide): RedirectResponse
    {
        Storage::disk('public')->delete($slide->image_path);
        $slide->delete();

        return redirect()->route('admin.slides.index')->with('status', 'Slide deleted.');
    }

    private function validated(Request $request, bool $required = true): array
    {
        $data = $request->validate([
            'placement' => ['required', 'in:homepage_hero,homepage_banner'],
            'title' => ['nullable', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:200'],
            'image' => [$required ? 'required' : 'nullable', 'image', 'max:4096'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'display_order' => ['required', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
