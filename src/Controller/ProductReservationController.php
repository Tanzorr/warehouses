<?php

namespace App\Controller;

use App\Entity\ProductReservation;
use App\Repository\ProductReservationRepository;
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
    public function __construct(
        private ReservationService           $reservationService,
        private ProductReservationRepository $repository
    )
    {
    }

    public function index(): Response
    {
        return $this->render('product_reservation/index.html.twig', [
            'controller_name' => 'ProductReservationController 4545',
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/product/reservation/add', name: 'app_product_reserve_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $result = $this->reservationService->reserve($data);

        return new JsonResponse(['message' => $result]);
    }


    #[Route('/product/reservation/{id}/status_update', name: 'app_product_reservation_status_update', methods: ['PUT'])]
    public function update(ProductReservation $reservation, Request $request): JsonResponse
    {
        $status = $request->toArray()['status'] ?? null;
        //if status is commited, check if reservation is not expired
        // if reservation is expired return message reservation expired
        //if reservation is not expired, update status to committed
        // call recalculate stock service
       // $this->repository->updateStatus($status, $reservation);
        return new JsonResponse(['message' => $this->reservationService->updateStatus($reservation, $status) ], Response::HTTP_OK);
    }
}
