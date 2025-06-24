<?php

namespace App\DTO;

use App\Entity\Product;

class ReserveInput
{
    /**
     * @var Product[]
     */
    public array $products = [];

    public int $warehouseId = 0;

    public string $comment = '';
}
