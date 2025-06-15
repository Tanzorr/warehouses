<?php

namespace App\Entity;

use AllowDynamicProperties;
use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties]
#[ORM\Entity]
class InventoryTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $reservationId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?int $warehouse_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    public function __construct(
        ?int $id = null,
        ?int $reservation_id = null,
        ?\DateTimeImmutable $created_at = null,
        ?int $warehouse_id = null,
        ?string $comment = null
    ) {
        $this->id = $id;
        $this->created_at = $created_at ?: new \DateTimeImmutable();
        $this->warehouse_id = $warehouse_id;
        $this->reservationId = $reservation_id ?? 0;
        $this->comment = $comment;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getReservationId(): ?int
    {
        return $this->reservationId;
    }

    public function setReservationId(int $reservation_id): static
    {
        $this->reservationId = $reservation_id;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getWarehouseId(): ?int
    {
        return $this->warehouse_id;
    }

    public function setWarehouseId(int $warehouse_id): static
    {
        $this->warehouse_id = $warehouse_id;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;
        return $this;
    }
}
