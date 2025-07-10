<?php

namespace App\Constans;

class ReservationStatusMessage
{
    public const NO_CHANGE       = 'No change needed';
    public const EXPIRED         = 'Expired';
    public const COMMITTED       = 'Committed';
    public const CANCELED        = 'Canceled';
    public const STATUS_UPDATED  = 'Status updated';
    public const STATUS_COMMITTED = 'Status committed';
    public const INVALID_CHANGE  = 'Invalid status change';

    // Варіанти від stockService
    public const STOCK_ALREADY_COMMITTED = 'Already committed';
    public const STOCK_OUT_OF_STOCK      = 'Out of stock';
    // ...додай інші при потребі
    const STATUS_PENDING = 'Pending';
}
