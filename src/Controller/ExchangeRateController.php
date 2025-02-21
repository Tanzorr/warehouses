<?php

namespace App\Controller;

use App\Service\ExchangeRateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ExchangeRateController extends AbstractController
{
    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/exchange/rate', name: 'app_exchange_rate')]
    public function index(ExchangeRateService $rateService): JsonResponse
    {
        $rateService->updateRates();

            return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ExchangeRateController.php',
        ]);
    }
}
