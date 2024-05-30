<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Promocode;
use App\Entity\RequestTypes\Product\ProductCalculatePrice;
use App\Entity\TaxNumber;
use App\Services\Product\PriceCalculator;
use App\Services\Product\TaxMaskConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/calculate-price', name: 'app_product')]
    public function calculatePrice(
        EntityManagerInterface                     $entityManager,
        #[MapRequestPayload] ProductCalculatePrice $request,
        PriceCalculator                            $priceCalculator,
        TaxMaskConverter                           $taxMaskConverter
    ): JsonResponse
    {
        $response = [
            'message' => '',
            'status'  => 400,
        ];

        $product = $entityManager->getRepository(Product::class)->find($request->product);

        if (!$product) {
            $response['message'] = 'Product not found';
            return new JsonResponse($response, 400);
        }

        if ($request->couponCode) {
            $promocode = $entityManager->getRepository(Promocode::class)->findOneBy(['name' => $request->couponCode]);

            if (!$promocode) {
                $response['message'] = 'Coupon not found';
                return new JsonResponse($response, 400);
            }
        }

        $taxNumbers = $entityManager->getRepository(TaxNumber::class)->findAll();

        foreach ($taxNumbers as $taxNumber) {
            $mask = $taxNumber->getMask();
            $regex = $taxMaskConverter->convertMaskToRegex($mask);
            if (preg_match("/$regex/", $request->taxNumber)) {
                $currentTaxNumber = $taxNumber;
            }
        }

        if (!isset($currentTaxNumber)) {
            $response['message'] = 'Tax number not found';
            return new JsonResponse($response, 400);
        }


        return $this->json([
            'status'  => 200,
            'product' => $product->getName(),
            'price'   => $priceCalculator->calculatePrice($product->getPrice(), $currentTaxNumber->getPercent(), $promocode ?? null)
        ]);

    }
}
