<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionPlanController extends AdminController
{
    public function index(): View
    {
        return view('admin.subscription-plans.index', ['plans' => SubscriptionPlan::orderBy('duration_days')->get()]);
    }

    public function create(): View
    {
        return view('admin.subscription-plans.form', ['plan' => new SubscriptionPlan()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        SubscriptionPlan::create($data);

        return redirect()->route('admin.subscription-plans.index')->with('status', 'Plan created.');
    }

    public function edit(SubscriptionPlan $subscriptionPlan): View
    {
        return view('admin.subscription-plans.form', ['plan' => $subscriptionPlan]);
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $subscriptionPlan->update($this->validated($request));

        return redirect()->route('admin.subscription-plans.index')->with('status', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $subscriptionPlan->update(['is_active' => false]); // soft-disable — existing subscriptions on this plan must stay intact

        return redirect()->route('admin.subscription-plans.index')->with('status', 'Plan disabled.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'billing_period' => ['required', 'in:weekly,monthly'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:31'],
            'price_cents' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'features' => ['nullable', 'string'],
        ]);

        $data['features'] = $data['features']
            ? array_values(array_filter(array_map('trim', explode("\n", $data['features']))))
            : [];

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
