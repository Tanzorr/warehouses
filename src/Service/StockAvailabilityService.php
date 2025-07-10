<?php

namespace App\Service;

use App\Entity\ProductReservation;
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

        $stocks = $this->repository->findByProductInStocks($productId);
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

    public function commitReservation(ProductReservation $reservation): string
    {
        try {
            foreach ($reservation->getItems() as $item) {
                $product = $item->getProduct();
                $amount = $item->getAmount();
                $stocks = $this->repository->findByProductInStocks($product->getId());
                $this->updatesStocksProductsAmount($stocks, $amount);
            }
            return 'Commited';
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    private function updatesStocksProductsAmount($stocks, int $amount): void
    {
        foreach ($stocks as $stock) {
            if ($stock->getAmount() >= $amount) {
                $stock->setAmount($stock->getAmount() - $amount);
                $this->repository->save($stock);
                return;
            } else {
                $amount -= $stock->getAmount();
                $stock->setAmount(0);
                $this->repository->save($stock);
            }
        }
    }
}
