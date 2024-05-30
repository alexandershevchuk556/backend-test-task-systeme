<?php

namespace App\Services\Payment;

use App\Services\Payment\PaymentProcessors\PayPalProcessor;
use App\Services\Payment\PaymentProcessors\StripeProcessor;

class PaymentProvider
{

    private static array $paymentProcessors = [
        'paypal' => PayPalProcessor::class,
        'stripe' => StripeProcessor::class
    ];

    public function getPaymentProcessor($name)
    {
        if (!isset(self::$paymentProcessors[$name])) {
            throw new \Exception('Payment processor not found');
        }
        return new self::$paymentProcessors[$name];
    }
}