<?php

namespace App\Providers;

use App\Services\Payments\ManualPaymentGateway;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Swap ManualPaymentGateway for a real driver (Stripe/Paystack/etc.)
     * here once a payment gateway is integrated — see interface docblock.
     */
    public array $bindings = [
        PaymentGatewayInterface::class => ManualPaymentGateway::class,
    ];
}
