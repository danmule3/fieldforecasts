<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\Sport;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeamController extends AdminController
{
    public function index(): View
    {
        return view('admin.teams.index', ['teams' => Team::with(['sport', 'country'])->orderBy('name')->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.teams.form', $this->formData(new Team()));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        Team::create($data);

        return redirect()->route('admin.teams.index')->with('status', 'Team created.');
    }

    public function edit(Team $team): View
    {
        return view('admin.teams.form', $this->formData($team));
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        $team->update($this->validated($request));

        return redirect()->route('admin.teams.index')->with('status', 'Team updated.');
    }

    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        return redirect()->route('admin.teams.index')->with('status', 'Team deleted.');
    }

    private function formData(Team $team): array
    {
        return [
            'team' => $team,
            'sports' => Sport::orderBy('name')->get(),
            'countries' => Country::orderBy('name')->get(),
        ];
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'sport_id' => ['required', 'integer', 'exists:sports,id'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:120'],
            'short_name' => ['nullable', 'string', 'max:10'],
        ]);
    }
}
