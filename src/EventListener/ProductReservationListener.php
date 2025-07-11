<?php

namespace App\EventListener;

use App\Entity\ProductReservation;
use App\Service\StockAvailabilityService;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ProductReservationListener
{
    public function __construct(
        protected StockAvailabilityService $stockAvailabilityService
    ) {}

    public function preUpdate(ProductReservation $productReservation, PreUpdateEventArgs $args): void
    {
        if (
            $args->hasChangedField('status')
            && $productReservation->getStatus() === ProductReservation::STATUS_COMMITTED
        ) {
            $this->stockAvailabilityService->commitReservation($productReservation);
        }
    }
}
