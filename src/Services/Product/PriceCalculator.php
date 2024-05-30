<?php

namespace App\Services\Product;

use App\Entity\Product;
use App\Entity\Promocode;
use App\Entity\TaxNumber;
use Doctrine\ORM\EntityManagerInterface;


class PriceCalculator
{

    public function calculatePrice($product, $couponCode, $taxNumber, EntityManagerInterface $entityManager, TaxMaskConverter $taxMaskConverter)
    {

        $product = $entityManager->getRepository(Product::class)->find($product);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        if ($couponCode) {
            $promocode = $entityManager->getRepository(Promocode::class)->findOneBy(['name' => $couponCode]);

            if (!$promocode) {
                throw new \Exception('Coupon not found');
            }
        }

        $taxNumbers = $entityManager->getRepository(TaxNumber::class)->findAll();

        foreach ($taxNumbers as $t) {
            $mask = $t->getMask();
            $regex = $taxMaskConverter->convertMaskToRegex($mask);
            if (preg_match("/$regex/", $taxNumber)) {
                $currentTaxNumber = $t;
            }
        }

        if (!isset($currentTaxNumber)) {
            throw new \Exception('Tax number not found');
        }

        return $this->calculateFinalPrice($product->getPrice(), $currentTaxNumber->getPercent(), $promocode ?? null);
    }

    public function calculateFinalPrice($productPrice, $taxPercent, Promocode $promocode = null)
    {

        if ($promocode) {
            if ($promocode->getType() == 'percent') {
                $productPrice = $productPrice - $productPrice * ($promocode->getDenomination() / 100);
            } elseif ($promocode->getType() == 'fixed') {
                $productPrice = $productPrice - $promocode->getDenomination();
            }
        }

        if ($productPrice < 0) $productPrice = 0;

        return $productPrice + $productPrice * ($taxPercent / 100);

    }

}