<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use App\Controller\ProductReservationController;
use App\DTO\ReserveInput;
use Doctrine\ORM\Mapping as ORM;


#[ApiResource(
    operations: [
    new Post(routeName: 'app_product_reserve_add',
        controller: ProductReservationController::class,
        input: ReserveInput::class
    ),
    new Delete(uriTemplate: '/product/reservation/{id}',
        routeName: 'app_product_reservation_delete',
        requirements: ['id' => '\d+'],
        controller: ProductReservationController::class,
        name: 'app_product_reservation_delete'
    )
])]

#[ORM\Entity]
class ProductReservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $reserved_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $released_at = null;

    #[ORM\ManyToOne(targetEntity: Warehouse::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Warehouse $warehouse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getReservedAt(): ?\DateTimeImmutable
    {
        return $this->reserved_at;
    }

    public function setReservedAt(\DateTimeImmutable $reserved_at): self
    {
        $this->reserved_at = $reserved_at;
        return $this;
    }

    public function getReleasedAt(): ?\DateTimeImmutable
    {
        return $this->released_at;
    }

    public function setReleasedAt(?\DateTimeImmutable $released_at): self
    {
        $this->released_at = $released_at;
        return $this;
    }

    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(Warehouse $warehouse): self
    {
        $this->warehouse = $warehouse;
        return $this;
    }
    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function __toString(): string
    {
        return $this->getComment() ?? 'Reservation ' . $this->getId() . ' at ' . $this->getReservedAt()->format('Y-m-d H:i:s');
    }
}
