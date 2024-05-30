<?php

namespace App\Services\Payment\PaymentProcessors;

use JetBrains\PhpStorm\ArrayShape;

interface PaymentProcessor
{
    #[ArrayShape(['message' => "string", 'status' => "string"])]
    public function pay($price): array;
}