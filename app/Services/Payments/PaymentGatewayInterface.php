<?php

namespace App\Services\Payments;

use App\Models\Payment;

/**
 * Seam for the real payment gateway integration the brief defers
 * ("Integrate payment gateways later"). `charge()` must be idempotent
 * per Payment row (drivers should no-op or return the existing result
 * if called twice for the same payment) so retried requests never
 * double-charge once a real gateway is wired in.
 */
interface PaymentGatewayInterface
{
    public function charge(Payment $payment): PaymentResult;
}
