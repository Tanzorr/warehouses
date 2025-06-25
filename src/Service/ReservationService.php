<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\ProductReservationItemRepository;
use App\Repository\ProductReservationRepository;
use App\Repository\WarehouseRepository;

class ReservationService
{
    public function __construct(
        private readonly ProductReservationRepository $reservationRepository,
        private readonly ProductReservationItemRepository $productReservationItemRepository,
        private readonly WarehouseRepository $warehouseRepository,
        private readonly ProductRepository $productRepository,
    )
    {
    }

    /**
     *
     * @param array $data
     * @return string ID резервації
     * @throws \InvalidArgumentException
     */
    public function reserve(array $data): string
    {
        $warehouse = $this->warehouseRepository->find($data['warehouseId'] ?? null);
        if (!$warehouse) {
            throw new \InvalidArgumentException('Warehouse not found');
        }

        $productReservation = $this->reservationRepository->create(
            $warehouse,
            $data['comment'] ?? null
        );
        $this->reservationRepository->save($productReservation);

        $products = $data['products'] ?? [];
        if (empty($products)) {
            throw new \InvalidArgumentException('products array is required');
        }

        foreach ($products as $product) {
            $productId = $product['id'] ?? null;
            $quantity = $product['amount'] ?? 0;

            if (!$productId || $quantity <= 0) {
                dd($product, $productId, $quantity);
                throw new \InvalidArgumentException('Invalid product data: id and quantity required');
            }

            $product = $this->productRepository->getOrFailById($productId);

            if (!$product) {
                throw new \InvalidArgumentException(sprintf('Product with ID %d not found', $productId));
            }


            $reservationItem = $this->productReservationItemRepository->create(
                $product,
                $productReservation,
                $quantity
            );


            $this->productReservationItemRepository->save($reservationItem);
        }

        return (string)$productReservation->getId();
    }
}
