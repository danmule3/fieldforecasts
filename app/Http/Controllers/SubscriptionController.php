<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class SubscriptionController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptions)
    {
    }

    public function index(): View
    {
        return view('subscriptions.plans', [
            'plans' => SubscriptionPlan::where('is_active', true)->orderBy('duration_days')->get(),
        ]);
    }

    /**
     * Real gateway UI (card entry, redirect-to-checkout, etc.) is added
     * when a live processor replaces ManualPaymentGateway — for now this
     * charges instantly so the subscription lifecycle is fully testable.
     */
    public function store(SubscribeRequest $request): RedirectResponse
    {
        $plan = SubscriptionPlan::where('slug', $request->validated('plan'))->firstOrFail();

        try {
            $this->subscriptions->subscribe($request->user(), $plan);
        } catch (RuntimeException $e) {
            return back()->withErrors(['plan' => $e->getMessage()]);
        }

        return redirect()->route('dashboard')->with('status', 'subscription-activated');
    }

    public function mine(Request $request): View
    {
        $subscriptions = $request->user()->subscriptions()
            ->with(['plan', 'payments'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('subscriptions.mine', ['subscriptions' => $subscriptions]);
    }

    public function cancel(Request $request, Subscription $subscription): RedirectResponse
    {
        $this->authorize('cancel', $subscription);

        $this->subscriptions->cancel($subscription);

        return back()->with('status', 'subscription-cancelled');
    }
}
