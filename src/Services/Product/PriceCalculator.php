<?php

namespace App\Services\Product;

use App\Entity\Promocode;

class PriceCalculator
{

    public function calculatePrice($productPrice, $taxPercent, Promocode $promocode = null)
    {

        if ($promocode) {
            if ($promocode->getType() == 'percent') {
                $productPrice = $productPrice - $productPrice * ($promocode->getDenomination() / 100);
            } elseif ($promocode->getType() == 'fixed') {
                $productPrice = $productPrice - $promocode->getDenomination();
            }
        }

        return $productPrice + $productPrice * ($taxPercent / 100);

    }

}