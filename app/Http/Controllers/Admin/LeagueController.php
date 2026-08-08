<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\League;
use App\Models\Sport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LeagueController extends AdminController
{
    public function index(): View
    {
        return view('admin.leagues.index', ['leagues' => League::with(['sport', 'country'])->orderBy('name')->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.leagues.form', $this->formData(new League()));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        League::create($data);

        return redirect()->route('admin.leagues.index')->with('status', 'League created.');
    }

    public function edit(League $league): View
    {
        return view('admin.leagues.form', $this->formData($league));
    }

    public function update(Request $request, League $league): RedirectResponse
    {
        $league->update($this->validated($request));

        return redirect()->route('admin.leagues.index')->with('status', 'League updated.');
    }

    public function destroy(League $league): RedirectResponse
    {
        $league->delete();

        return redirect()->route('admin.leagues.index')->with('status', 'League deleted.');
    }

    private function formData(League $league): array
    {
        return [
            'league' => $league,
            'sports' => Sport::orderBy('name')->get(),
            'countries' => Country::orderBy('name')->get(),
        ];
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'sport_id' => ['required', 'integer', 'exists:sports,id'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:120'],
            'season' => ['nullable', 'string', 'max:20'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
