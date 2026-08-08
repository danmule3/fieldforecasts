<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CountryController extends AdminController
{
    public function index(): View
    {
        return view('admin.countries.index', ['countries' => Country::orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.countries.form', ['country' => new Country()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);

        Country::create($data);

        return redirect()->route('admin.countries.index')->with('status', 'Country created.');
    }

    public function edit(Country $country): View
    {
        return view('admin.countries.form', ['country' => $country]);
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $country->update($this->validated($request));

        return redirect()->route('admin.countries.index')->with('status', 'Country updated.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        $country->delete();

        return redirect()->route('admin.countries.index')->with('status', 'Country deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'iso_code' => ['nullable', 'string', 'max:3'],
        ]);
    }
}
