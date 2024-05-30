<?php

namespace App\Services\Payment\PaymentProcessors;

use JetBrains\PhpStorm\ArrayShape;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PayPalProcessor implements PaymentProcessor
{
    #[ArrayShape(['message' => "string", 'status' => "string"])]
    public function pay($price): array
    {
        $processor = new PaypalPaymentProcessor;
        try {
            $processor->pay($price);
        } catch (\Exception $e) {
            return ['message' => $e->getMessage(), 'status' => 'error'];
        }

        return ['message' => 'Payment passed!', 'status' => 'ok'];

    }
}