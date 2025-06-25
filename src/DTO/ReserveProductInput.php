<?php

namespace App\DTO;

class ReserveProductInput
{
    public int $productId;
    public int $amount;

    public function __construct(int $productId, int $amount)
    {
        $this->productId = $productId;
        $this->amount = $amount;
    }
}
