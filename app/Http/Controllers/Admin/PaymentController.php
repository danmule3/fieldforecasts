<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Read-only by design for this release — refunds/adjustments require a
 * real payment gateway's refund API (Module design notes, Module 4)
 * which doesn't exist yet under ManualPaymentGateway. Once a live
 * gateway is wired in, add a refund() action here calling the
 * gateway's refund method through the same PaymentGatewayInterface seam.
 */
class PaymentController extends AdminController
{
    public function index(Request $request): View
    {
        $payments = Payment::with(['user', 'subscription.plan'])
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.payments.index', ['payments' => $payments]);
    }
}
