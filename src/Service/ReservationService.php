<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductReservation;
use App\Repository\ProductRepository;
use App\Repository\ProductReservationRepository;

class ReservationService
{
    public function __construct(
        private readonly ProductReservationRepository $reservationRepository,
        private readonly ProductRepository            $productRepository,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function reserve(
        int $productId,
        int $warehouseId,
        int $quantity
    ): string
    {
        try {
            $product = $this->productRepository->getOrFail($productId);
            $this->assertSufficientStock($product, $quantity);
            $this->assertSufficientAvailableAfterReservations($product, $quantity);

            $productReservation = $this->reservationRepository->create(
                $product,
                $warehouseId,
                $quantity,
                'Product reservation for product ID ' . $productId . ' in warehouse ID ' . $warehouseId
            );
            $this->reservationRepository->save($productReservation);
            return 'Reservation successful.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }


    private function assertSufficientStock(Product $product, int $quantity): void
    {
        if ($product->getStockQuantity() < $quantity) {
            throw new \Exception('Not enough stock available for reservation.');
        }
    }

    private function getReservedQuantity(Product $product): float
    {
        $reservations = $this->reservationRepository->findByProductId($product->getId());
        return array_sum(array_map(
            fn(ProductReservation $reservation) => $reservation->getQuantity(),
            $reservations
        ));
    }

    private function assertSufficientAvailableAfterReservations(Product $product, int $quantity): void
    {
        $available = $product->getStockQuantity() - $this->getReservedQuantity($product);
        if ($available < $quantity) {
            throw new \Exception('Not enough stock available after considering existing reservations.');
        }
    }
}
