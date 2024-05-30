<?php

namespace App\Services\Payment\PaymentProcessors;

use JetBrains\PhpStorm\ArrayShape;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripeProcessor implements PaymentProcessor
{
    #[ArrayShape(['message' => "string", 'status' => "string"])]
    public function pay($price): array
    {
        $processor = new StripePaymentProcessor();
        $payment = $processor->processPayment($price);

        if ($payment) {
            return ['message' => 'Payment passed!', 'status' => 'ok'];
        }

        return ['message' => 'Price too low', 'status' => 'error'];

    }
}