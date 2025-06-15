<?php

namespace App\Service;

use App\Entity\InventoryTransaction;
use App\Entity\Product;
use App\Entity\ProductReservation;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    public function __construct(private readonly EntityManagerInterface $em)
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
            $product = $this->getProduct($productId);
            $this->assertSufficientStock($product, $quantity);
            $this->assertSufficientAvailableAfterReservations($product, $quantity);

            $productReservation = (new ProductReservation())
                ->setProductId($product->getId())
                ->setQuantity($quantity)
                ->setReservedAt(new \DateTimeImmutable())
                ->setComment('Reserved by user');

            $this->em->persist($productReservation);
            $this->em->flush();
            $productReservationId = $productReservation->getId();
            $this->andTransaction($warehouseId, $productReservationId);
            return 'Reservation successful.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function andTransaction(int $warehouseId, int $reservationId): void
    {
        try {
            $transaction = new InventoryTransaction();
            $transaction->setWarehouseId($warehouseId);
            $transaction->setReservationId($reservationId);
            $this->em->persist($transaction);
            $this->em->flush();
            return;
        }catch (\Exception $e){
            return;
        }
    }
    private function getProduct(int $productId): Product
    {
        $product = $this->em->getRepository(Product::class)->find($productId);
        if (!$product) {
            throw new \Exception('Product not found.');
        }
        return $product;
    }

    private function assertSufficientStock(Product $product, int $quantity): void
    {
        if ($product->getStockQuantity() < $quantity) {
            throw new \Exception('Not enough stock available for reservation.');
        }
    }

    private function getReservedQuantity(Product $product): float
    {
        $reservations = $this->em->getRepository(ProductReservation::class)
            ->findBy(['product_id' => $product->getId()]);
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
