<?php

namespace App\DTO;


class ReserveInput
{
    public int $product_id = 0;
    public string $warehouse_title= 'Warehouse 1';
    public int $quantity = 0;
    public string $comment = '';
}
