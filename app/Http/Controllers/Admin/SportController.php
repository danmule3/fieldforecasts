<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SportController extends AdminController
{
    public function index(): View
    {
        return view('admin.sports.index', ['sports' => Sport::orderBy('display_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.sports.form', ['sport' => new Sport()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);

        Sport::create($data);

        return redirect()->route('admin.sports.index')->with('status', 'Sport created.');
    }

    public function edit(Sport $sport): View
    {
        return view('admin.sports.form', ['sport' => $sport]);
    }

    public function update(Request $request, Sport $sport): RedirectResponse
    {
        $sport->update($this->validated($request));

        return redirect()->route('admin.sports.index')->with('status', 'Sport updated.');
    }

    public function destroy(Sport $sport): RedirectResponse
    {
        $sport->delete();

        return redirect()->route('admin.sports.index')->with('status', 'Sport deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'icon' => ['nullable', 'string', 'max:60'],
            'display_order' => ['required', 'integer', 'min:0'],
        ]);

        // Checkboxes are absent from the request when unchecked, so
        // validate() alone would silently leave is_active unchanged on
        // update — read it explicitly instead.
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
