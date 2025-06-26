<?php

namespace App\Service;

use App\Repository\StockAvailabilityRepository;

class StockAvailabilityService
{
    public function __construct(private StockAvailabilityRepository $repository) {}

    public function checkAccessedProductsInWarehouse(int $productId, int $warehouseId, int $amount): bool
    {
        $stock = $this->repository->findByProductWarehouse($productId, $warehouseId);

        if (!$stock) {
            throw new \InvalidArgumentException('No stock information available for the product in the warehouse');
        }

        return $stock->getAmount() >= $amount;
    }

    public function recalculateStock(int $productId, int $warehouseId, int $amount): void
    {
        $stock = $this->repository->findByProductWarehouse($productId, $warehouseId);

        if (!$stock) {
            throw new \InvalidArgumentException('No stock information available for the product in the warehouse');
        }

        $newAmount = $stock->getAmount() - $amount;
        if ($newAmount < 0) {
            throw new \InvalidArgumentException('Insufficient stock for the product in the warehouse');
        }

        $stock->setAmount($newAmount);
        $this->repository->save($stock);
    }
}
