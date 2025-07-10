<?php

namespace App\Service;

use App\Constans\ReservationStatusMessage;
use App\Repository\ProductRepository;
use App\Repository\ProductReservationItemRepository;
use App\Repository\ProductReservationRepository;
use App\Entity\ProductReservation;
use App\Entity\Product;

readonly class ReservationService
{
    public function __construct(
        private ProductReservationRepository $reservationRepository,
        private ProductReservationItemRepository $itemRepository,
        private ProductRepository $productRepository,
        private StockAvailabilityService $stockService
    ) {}

    /**
     * @throws \Exception
     */
    public function reserve(array $data): string
    {
        $expandedAt = (new \DateTimeImmutable())->add(new \DateInterval('PT15M'));
        $reservation = $this->createReservation( $data['comment'] ?? null, $expandedAt);

        foreach ($data['products'] as $item) {
            $product = $this->getProduct($item['id']);
            $error = $this->checkAccessedProducts($product->getId(), $item['amount']);

            if($error){
                $this->canselReservation($reservation);
                return $error;
            }

            $this->addReservationItem($product, $reservation, $item['amount']);
        }

        return (string) $reservation->getId();
    }

    private function checkAccessedProducts(int $productId, int $amount): string | null
    {
        if (!$this->stockService->checkAccessedProductsInStock($productId,  $amount)) {
            return sprintf(
                '{"error": "Not enough stock for product ID %d in warehouse ID %d"}',
                $productId,
            );
        }

        return null;
    }

    /**
     * @throws \Exception
     */
    private function getProduct(int $id): Product
    {
        return $this->productRepository->getOrFailById($id);
    }

    private function createReservation( ?string $comment, ?\DateTimeImmutable $expiredAt): ProductReservation
    {
        $reservation = $this->reservationRepository->create($comment, $expiredAt);
        $this->reservationRepository->save($reservation);
        return $reservation;
    }

    private function addReservationItem(Product $product, ProductReservation $reservation, int $amount): void
    {
        $item = $this->itemRepository->create($product, $reservation, $amount);
        $this->itemRepository->save($item);
    }

    public function canselReservation(ProductReservation $reservation): void
    {
        $this->reservationRepository->remove($reservation);
    }

    public function updateStatus(ProductReservation $reservation, string $newStatus): string
    {
        $currentStatus = $reservation->getStatus();

        if ($currentStatus === $newStatus) {
            return ReservationStatusMessage::NO_CHANGE;
        }

        return  match ($currentStatus) {
            ProductReservation::STATUS_EXPIRED => ReservationStatusMessage::EXPIRED,
            ProductReservation::STATUS_COMMITTED => ReservationStatusMessage::COMMITTED,
            ProductReservation::STATUS_CANCELED => ReservationStatusMessage::CANCELED,
            ProductReservation::STATUS_PENDING => $this->handlingPending($reservation, $newStatus),
            default => ReservationStatusMessage::INVALID_CHANGE,
        };
    }

    private function handlingPending(ProductReservation $reservation, string $newStatus):string
    {
        if($newStatus !== ProductReservation::STATUS_COMMITTED) {
          return $this->updateReservationStatus($reservation, $newStatus, ReservationStatusMessage::STATUS_PENDING);
        }

        $result = $this->stockService->commitReservation($reservation);

        if($result === ProductReservation::STATUS_COMMITTED) {
            return $this->updateReservationStatus($reservation, $newStatus, ReservationStatusMessage::STATUS_COMMITTED);
        }

        return  $result;
    }

    private function updateReservationStatus(
        ProductReservation $reservation,
        string $newStatus,
        string $message
    ):string
    {
        $reservation->setStatus($newStatus);
        $this->reservationRepository->save($reservation);
        return $message;
    }
}
