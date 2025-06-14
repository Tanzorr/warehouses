<?php

namespace App\DTO;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\ProductReservationController;
use App\Entity\Product;


//#[ApiResource(
//    operations: [
//        new Post(
//            uriTemplate: '/product/reservation',
//            controller: ProductReservationController::class . '::reserve',
//            name: 'product_reserve'
//        )
//    ]
//)]
class ReserveInput
{
    public int $productId = 0;
    public int $quantity = 0;
}
