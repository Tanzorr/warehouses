<?php

namespace App\EventSubscriber;
use App\Entity\ProductReservation;
use App\Service\StockAvailabilityService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\CompletedEvent;

readonly class ProductReservationWorkflowSubscriber implements EventSubscriberInterface
{

    public function __construct(private StockAvailabilityService $stockAvailabilityService)
    {}

    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.product_reservation_workflow.completed.commit' => 'onCommit',
            'workflow.product_reservation_workflow.completed.cancel' => 'onCancel',
        ];
    }

    public function onCommit(CompletedEvent $event): void
    {
        $reservation = $this->getReservation($event);

        if ($reservation->getStatus() !== ProductReservation::STATUS_PENDING) {
            return;
        }

        $this->stockAvailabilityService->commitReservation($reservation);
        $reservation->setStatus(ProductReservation::STATUS_COMMITTED);

    }

    public function onCancel(CompletedEvent $event): ProductReservation | null
    {
        $reservation = $this->getReservation($event);
        if ($reservation->getStatus() !== ProductReservation::STATUS_PENDING) {
            return $reservation;
        }

        return null;
    }

    private function getReservation(CompletedEvent $event): ProductReservation
    {
        $subject = $event->getSubject();
        if (!$subject instanceof ProductReservation) {
            throw new \InvalidArgumentException('Expected ProductReservation, got ' . get_class($subject));
        }
        return $subject;
    }
}
