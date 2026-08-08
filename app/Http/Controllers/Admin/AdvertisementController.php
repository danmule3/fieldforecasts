<?php

namespace App\Http\Controllers\Admin;

use App\Models\Advertisement;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdvertisementController extends AdminController
{
    public function __construct(private readonly ImageOptimizer $imageOptimizer)
    {
    }

    public function index(): View
    {
        return view('admin.advertisements.index', ['advertisements' => Advertisement::orderByDesc('created_at')->get()]);
    }

    public function create(): View
    {
        return view('admin.advertisements.form', ['advertisement' => new Advertisement()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image_path'] = $this->imageOptimizer->storeOptimized($request->file('image'), 'advertisements', 'public');
        unset($data['image']);

        Advertisement::create($data);

        return redirect()->route('admin.advertisements.index')->with('status', 'Advertisement created.');
    }

    public function edit(Advertisement $advertisement): View
    {
        return view('admin.advertisements.form', ['advertisement' => $advertisement]);
    }

    public function update(Request $request, Advertisement $advertisement): RedirectResponse
    {
        $data = $this->validated($request, required: false);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($advertisement->image_path);
            $data['image_path'] = $this->imageOptimizer->storeOptimized($request->file('image'), 'advertisements', 'public');
        }
        unset($data['image']);

        $advertisement->update($data);

        return redirect()->route('admin.advertisements.index')->with('status', 'Advertisement updated.');
    }

    public function destroy(Advertisement $advertisement): RedirectResponse
    {
        Storage::disk('public')->delete($advertisement->image_path);
        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('status', 'Advertisement deleted.');
    }

    private function validated(Request $request, bool $required = true): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'placement' => ['required', 'string', 'max:60'],
            'image' => [$required ? 'required' : 'nullable', 'image', 'max:2048'],
            'target_url' => ['required', 'url', 'max:255'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
