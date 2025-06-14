<?php

namespace App\Service;

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
    public function reserve(int $productId, int $quantity): void
    {
        $product = $this->em->getRepository(Product::class)->find($productId);
        $availableQuantity = $product->getStockQuantity();
        if($availableQuantity < $quantity) {
            throw new \Exception('Not enough stock available for reservation.');
        }

        $reservations = $this->em->getRepository(ProductReservation::class)->findBy(['product_id' => $product->getId()]);
        $reservedQuantity = array_reduce($reservations, function (float $carry, ProductReservation $reservation) {
            return $carry + $reservation->getQuantity();
        }, 0.0);

        $totalAvailableQuantity = $availableQuantity - $reservedQuantity;
        if ($totalAvailableQuantity < $quantity) {
            throw new \Exception('Not enough stock available after considering existing reservations.');
        }

        // Create a new ProductReservation entity
        $productReservation = new ProductReservation();
        $productReservation->setProductId($product->getId());
        $productReservation->setQuantity($quantity);
        $productReservation->setReservedAt(new \DateTimeImmutable());
        $productReservation->setComment('Reserved by user'); // You can customize this comment as needed
        $this->em->persist($productReservation);
        $this->em->flush();
    }
}
