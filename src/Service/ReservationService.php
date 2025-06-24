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
            $productId = $data['product_id'] ?? null;
            $quantity = $data['quantity'] ?? 0;
            $comment = $data['comment'] ?? null;

            $product = $this->productRepository->getOrFailById($productId);
            $warehouseId = $this->warehouseRepository->getOrFailByTitle($data['warehouse_title'])->getId();

            $this->assertSufficientStock($product, $quantity);
            $this->assertSufficientAvailableAfterReservations($product, $quantity);

            $productReservation = $this->reservationRepository->create($product, $warehouseId, $quantity, $comment);
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
