<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\RequestTypes\Product\ProductCalculatePrice;
use App\Entity\RequestTypes\Product\ProductPurchase;
use App\Services\Payment\PaymentProcessors\PaymentProcessor;
use App\Services\Payment\PaymentProvider;
use App\Services\Product\PriceCalculator;
use App\Services\Product\TaxMaskConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/purchase', name: 'purchase')]
    public function purchase(
        EntityManagerInterface               $entityManager,
        #[MapRequestPayload] ProductPurchase $request,
        PriceCalculator                      $priceCalculator,
        TaxMaskConverter                     $taxMaskConverter,
        PaymentProvider                      $paymentProvider
    ): JsonResponse
    {
        try {
            $price = $priceCalculator->calculatePrice($request->product, $request->couponCode, $request->taxNumber, $entityManager, $taxMaskConverter);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status'  => 400,
                'message' => $e->getMessage()
            ], 400);
        }


        try {
            $paymentProcessor = $paymentProvider->getPaymentProcessor($request->paymentProcessor);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status'  => 400,
                'message' => $e->getMessage()
            ], 400);
        }

        $paymentResponse = $paymentProcessor->pay($price);

        if ($paymentResponse['status'] == 'error') {
            return new JsonResponse([
                'status'  => 400,
                'message' => $paymentResponse['message']
            ], 400);
        }


        return new JsonResponse([
            'status'  => 200,
            'product' => $entityManager->getRepository(Product::class)->find($request->product)->getName(),
            'price'   => $price,
            'paymentStatus' => $paymentResponse['message']
        ], 200);

    }

    #[Route('/calculate-price', name: 'app_product')]
    public function calculatePrice(
        EntityManagerInterface                     $entityManager,
        #[MapRequestPayload] ProductCalculatePrice $request,
        PriceCalculator                            $priceCalculator,
        TaxMaskConverter                           $taxMaskConverter
    ): JsonResponse
    {

        try {
            $price = $priceCalculator->calculatePrice($request->product, $request->couponCode, $request->taxNumber, $entityManager, $taxMaskConverter);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status'  => 400,
                'message' => $e->getMessage()
            ], 400);
        }

        return new JsonResponse([
            'status'  => 200,
            'product' => $entityManager->getRepository(Product::class)->find($request->product)->getName(),
            'price'   => $price
        ], 200);

    }
}
