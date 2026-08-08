<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Str;

/**
 * Stand-in gateway that marks every charge as immediately successful.
 * Exists so the subscription lifecycle (activation, expiry, renewal
 * reminders, billing history) can be built and tested end-to-end
 * before a real processor is wired in. Swap the binding in
 * PaymentServiceProvider for a StripeGateway/PaystackGateway/etc.
 * implementing the same interface — nothing else in the app changes.
 */
class ManualPaymentGateway implements PaymentGatewayInterface
{
    public function charge(Payment $payment): PaymentResult
    {
        return PaymentResult::success('manual_' . Str::uuid());
    }
}
