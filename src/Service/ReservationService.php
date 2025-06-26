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
        private ProductReservationRepository $reservationRepository,
        private ProductReservationItemRepository $itemRepository,
        private WarehouseRepository $warehouseRepository,
        private ProductRepository $productRepository,
        private StockAvailabilityService $stockService
    ) {}

    /**
     * @throws \Exception
     */
    public function reserve(array $data): string
    {
        $warehouse = $this->getWarehouse($data['warehouseId']);
        $reservation = $this->createReservation($warehouse, $data['comment'] ?? null);

        foreach ($data['products'] as $item) {
            $product = $this->getProduct($item['id']);
            $this->addReservationItem($product, $reservation, $item['amount']);
        }

        return (string) $reservation->getId();
    }

    private function getWarehouse(int $id): Warehouse
    {
        $warehouse = $this->warehouseRepository->find($id);
        if (!$warehouse) {
            throw new \InvalidArgumentException('Warehouse not found');
        }
        return $warehouse;
    }

    /**
     * @throws \Exception
     */
    private function getProduct(int $id): Product
    {
        $product = $this->productRepository->getOrFailById($id);
        if (!$product) {
            throw new \InvalidArgumentException("Product with ID $id not found");
        }
        return $product;
    }

    private function createReservation(Warehouse $warehouse, ?string $comment): ProductReservation
    {
        $reservation = $this->reservationRepository->create($warehouse, $comment);
        $this->reservationRepository->save($reservation);
        return $reservation;
    }

    private function addReservationItem(Product $product, ProductReservation $reservation, int $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }

        $warehouseId = $reservation->getWarehouse()->getId();
        if (!$this->stockService->checkAccessedProductsInWarehouse($product->getId(), $warehouseId, $amount)) {
            throw new \InvalidArgumentException("Not enough stock for product ID {$product->getId()} in warehouse ID $warehouseId");
        }

        $this->stockService->recalculateStock($product->getId(), $warehouseId, $amount);

        $item = $this->itemRepository->create($product, $reservation, $amount);
        $this->itemRepository->save($item);
    }
}
