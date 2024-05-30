<?php

namespace App\Services\Product;

class TaxMaskConverter
{
    public function convertMaskToRegex($mask)
    {
        $mask = str_replace('Y', '[A-Z]', $mask);
        return str_replace('X', '\d', $mask);
    }

}