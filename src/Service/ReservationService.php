<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\ProductReservationItemRepository;
use App\Repository\ProductReservationRepository;
use App\Repository\WarehouseRepository;
use App\Entity\ProductReservation;
use App\Entity\Product;
use App\Entity\Warehouse;

readonly class ReservationService
{
    public function __construct(
        private ProductReservationRepository     $reservationRepository,
        private ProductReservationItemRepository $productReservationItemRepository,
        private WarehouseRepository              $warehouseRepository,
        private ProductRepository                $productRepository
    ) {}

    /**
     * @return string Reservation ID
     * @throws \Exception
     */
    public function reserve($data): string
    {
        $warehouse = $this->findWarehouseOrFail($data['warehouseId']);
        $productReservation = $this->createAndSaveReservation($warehouse, $data['comment'] ?? null);

        foreach ($data['products'] as $productItem) {
            $product = $this->findProductOrFail($productItem['id']);
            $this->createAndSaveReservationItem($product, $productReservation, $productItem['amount']);
        }

        return (string) $productReservation->getId();
    }

    private function findWarehouseOrFail(int $warehouseId): Warehouse
    {
        $warehouse = $this->warehouseRepository->find($warehouseId);
        if (!$warehouse) {
            throw new \InvalidArgumentException('Warehouse not found');
        }
        return $warehouse;
    }

    /**
     * @throws \Exception
     */
    private function findProductOrFail(int $productId): Product
    {
        $product = $this->productRepository->getOrFailById($productId);
        if (!$product) {
            throw new \InvalidArgumentException(sprintf('Product with ID %d not found', $productId));
        }
        return $product;
    }

    private function createAndSaveReservation(Warehouse $warehouse, ?string $comment): ProductReservation
    {
        $reservation = $this->reservationRepository->create($warehouse, $comment);
        $this->reservationRepository->save($reservation);
        return $reservation;
    }

    private function createAndSaveReservationItem(Product $product, ProductReservation $reservation, int $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }
        $item = $this->productReservationItemRepository->create($product, $reservation, $amount);
        $this->productReservationItemRepository->save($item);
    }
}
