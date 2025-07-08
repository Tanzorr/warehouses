<?php

namespace App\Service;

use App\Repository\StockAvailabilityRepository;

readonly class StockAvailabilityService
{
    public function __construct(private StockAvailabilityRepository $repository)
    {
    }

    public function checkAccessedProductsInStock(int $productId, int $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }

        $stocks = $this->repository->findOneByProductInStocks($productId);
        return $this->getStocksAmount($stocks) > $amount;
    }

    private function getStocksAmount(array $stocks): int
    {
        $stocksAmount = 0;
        foreach ($stocks as $stock) {
            $stocksAmount += $stock->getAmount();
        }
        return $stocksAmount;
    }
}
