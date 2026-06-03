<?php

namespace App\Application\Command\CommitReservation;

use App\Entity\ProductReservation;
use App\Repository\ProductReservationRepository;
use App\Service\StockAvailabilityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CommitReservationHandler
{
    public function __construct(
        private ProductReservationRepository $reservations,
        private StockAvailabilityService     $stockService,
        private EntityManagerInterface       $entityManager,
    ) {}

    public function __invoke(CommitReservationCommand $command): void
    {
        $reservation = $this->reservations->find((int) $command->reservationId);

        if ($reservation === null) {
            // Already deleted or never existed — idempotent, not an error.
            return;
        }

        if ($reservation->getStatus() !== ProductReservation::STATUS_PENDING) {
            // Already committed or cancelled — idempotent.
            return;
        }

        $this->stockService->commitReservation($reservation);
        $reservation->setStatus(ProductReservation::STATUS_COMMITTED);
        $this->entityManager->flush();
    }
}
