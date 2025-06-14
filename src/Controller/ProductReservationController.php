<?php

namespace App\Controller;

use App\Service\ReservationService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class ProductReservationController extends AbstractController
{
    public function __construct(private ReservationService $reservationService)
    {
    }

    public function index(): Response
    {
        return $this->render('product_reservation/index.html.twig', [
            'controller_name' => 'ProductReservationController 4545',
        ]);
    }

    #[Route('/product/reservation', name: 'app_product_reserve', methods: ['POST'])]
    public function reserve(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $this->reservationService->reserve($data['productId'], $data['quantity']);

        return new JsonResponse(['status' => 'reserved']);
    }
}
