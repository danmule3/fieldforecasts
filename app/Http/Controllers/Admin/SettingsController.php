<?php

namespace App\Http\Controllers\Admin;

use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends AdminController
{
    public function __construct(private readonly SettingsService $settings)
    {
    }

    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => $this->settings->all()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:80'],
            'contact_email' => ['required', 'email'],
            'maintenance_mode' => ['nullable'],
        ]);

        $this->settings->set('site_name', $data['site_name']);
        $this->settings->set('contact_email', $data['contact_email']);
        $this->settings->set('maintenance_mode', $request->boolean('maintenance_mode') ? '1' : '0', 'boolean');

        return back()->with('status', 'Settings updated.');
    }
}
