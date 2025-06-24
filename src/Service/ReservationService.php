<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductReservation;
use App\Repository\ProductRepository;
use App\Repository\ProductReservationRepository;
use App\Repository\WarehouseRepository;

class ReservationService
{
    public function __construct(
        private readonly ProductReservationRepository $reservationRepository,
        private readonly ProductRepository            $productRepository,
        private readonly WarehouseRepository     $warehouseRepository,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function reserve(array $data): string
    {
        try {
            $products = $data['products'] ?? null;
            $quantity = $data['quantity'] ?? 0;
            $comment = $data['comment'] ?? null;

            $product = $this->productRepository->getOrFailById($products);


            $productReservation = $this->reservationRepository->create($product,  $quantity, $comment);
            $this->reservationRepository->save($productReservation);

            return 'Reservation successful.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }


}
