<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApiKey;
use App\Services\ApiKeyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Restricted to Administrator+ (not Editor) via the `can:manage-system`
 * route middleware (see routes/admin.php) — API credentials are
 * sensitive infrastructure config, not editorial content, so this
 * deliberately sits outside the Editor-accessible "Content" area
 * even though it lives in the same admin panel.
 */
class ApiKeyController extends AdminController
{
    public function __construct(private readonly ApiKeyService $apiKeys)
    {
    }

    public function index(): View
    {
        return view('admin.api-keys.index', [
            'apiKeys' => ApiKey::orderBy('provider')->get(),
            'providers' => array_keys(config('sports_api.providers')),
        ]);
    }

    public function create(): View
    {
        return view('admin.api-keys.form', [
            'apiKey' => new ApiKey(),
            'providers' => array_keys(config('sports_api.providers')),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'provider' => ['required', 'string', 'in:' . implode(',', array_keys(config('sports_api.providers')))],
            'label' => ['required', 'string', 'max:80'],
            'key_value' => ['required', 'string', 'max:500'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        ApiKey::create($data);
        $this->apiKeys->forgetCache($data['provider']);

        return redirect()->route('admin.api-keys.index')->with('status', 'API key saved.');
    }

    public function destroy(ApiKey $apiKey): RedirectResponse
    {
        $provider = $apiKey->provider;
        $apiKey->delete();
        $this->apiKeys->forgetCache($provider);

        return redirect()->route('admin.api-keys.index')->with('status', 'API key removed.');
    }

    public function toggleActive(ApiKey $apiKey): RedirectResponse
    {
        $apiKey->update(['is_active' => ! $apiKey->is_active]);
        $this->apiKeys->forgetCache($apiKey->provider);

        return back()->with('status', $apiKey->is_active ? 'API key activated.' : 'API key deactivated.');
    }
}
