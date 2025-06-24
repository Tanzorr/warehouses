<?php

namespace App\DTO;


class ReserveInput
{
    public int $product_id = 0;
    public int $warehouse_id = 0;
    public int $quantity = 0;
    public string $comment = '';
}
