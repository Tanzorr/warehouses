<?php

namespace App\Enum;

enum InventoryTransactionType: string
{
    case ORDER_RESERVATION = 'order_reservation';
    case PAYMENT_DELIVERY = 'payment_delivery';
}
