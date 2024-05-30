<?php

namespace App\Entity\RequestTypes\Product;

use App\Entity\RequestTypes\RequestType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class ProductPurchase extends RequestType
{
    public function __construct(
        #[NotBlank()]
        #[Type('int')]
        public readonly int     $product,

        #[NotBlank()]
        #[Type('string')]
        #[Regex('/^[A-Z]+\d*$/', 'Wrong tax number')]
        public readonly string  $taxNumber,

        #[NotBlank()]
        #[Type('string')]
        public readonly string $paymentProcessor,

        #[Type('string')]
        public readonly ?string $couponCode = null

    )
    {
    }
}