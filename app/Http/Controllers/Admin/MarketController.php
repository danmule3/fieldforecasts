<?php

namespace App\Http\Controllers\Admin;

use App\Models\Market;
use App\Models\Sport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MarketController extends AdminController
{
    public function index(): View
    {
        return view('admin.markets.index', ['markets' => Market::with('sport')->orderBy('display_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.markets.form', ['market' => new Market(), 'sports' => Sport::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['key'] = Str::slug($data['name'], '_');

        Market::create($data);

        return redirect()->route('admin.markets.index')->with('status', 'Market created.');
    }

    public function edit(Market $market): View
    {
        return view('admin.markets.form', ['market' => $market, 'sports' => Sport::orderBy('name')->get()]);
    }

    public function update(Request $request, Market $market): RedirectResponse
    {
        $market->update($this->validated($request));

        return redirect()->route('admin.markets.index')->with('status', 'Market updated.');
    }

    public function destroy(Market $market): RedirectResponse
    {
        $market->delete();

        return redirect()->route('admin.markets.index')->with('status', 'Market deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'sport_id' => ['nullable', 'integer', 'exists:sports,id'],
            'display_order' => ['required', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
