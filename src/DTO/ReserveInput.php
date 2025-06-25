<?php
namespace App\DTO;

use App\Entity\Product;

class ReserveInput
{
    /**
     * @var ReserveProductInput[]
     */
    public array $products;

    public int $warehouseId = 0;

    public string $comment = '';

    public function __construct()
    {
        $this->products = [
            new ReserveProductInput(1, 5),
            new ReserveProductInput(2, 3),
        ];
    }
}
