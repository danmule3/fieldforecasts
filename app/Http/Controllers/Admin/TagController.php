<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends AdminController
{
    public function index(): View
    {
        return view('admin.tags.index', ['tags' => Tag::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:40']]);
        $data['slug'] = Str::slug($data['name']);

        Tag::firstOrCreate(['slug' => $data['slug']], $data);

        return redirect()->route('admin.tags.index')->with('status', 'Tag created.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('status', 'Tag deleted.');
    }
}
