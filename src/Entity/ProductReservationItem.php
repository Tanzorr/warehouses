<?php

namespace App\Entity;

use App\Repository\ProductReservationItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductReservationItemRepository::class)]
class ProductReservationItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ProductReservation::class, inversedBy: "reservationItems")]
    private ProductReservation $productReservation;
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "reservationItems")]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public  function getProductReservation(): ProductReservation
    {
        return $this->productReservation;
    }

    public function setProductReservation(ProductReservation $productReservation): static
    {
        $this->productReservation = $productReservation;

        return $this;
    }

}
