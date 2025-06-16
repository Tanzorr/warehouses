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
    private ?int $entity_id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?int $warehouse_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $product_id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?string $entity_type = null;

    #[ORM\Column]
    private ?int $user_id = null;

    public function __construct(
        ?int                $id = null,
        ?int                $entity_id = null,
        ?\DateTimeImmutable $created_at = null,
        ?int                $warehouse_id = null,
        ?string             $comment = null,
        ?int                $product_id = null,
        ?int                $quantity = null,
        ?int                $user_id = null
        , ?string           $entity_type = null
    )
    {
        $this->id = $id;
        $this->created_at = $created_at ?: new \DateTimeImmutable();
        $this->warehouse_id = $warehouse_id;
        $this->entity_id = $entity_id;
        $this->comment = $comment;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
        $this->user_id = $user_id;
        $this->entity_type = $entity_type ?? 'inventory_transaction';
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

    public function getEntityId(): ?int
    {
        return $this->entity_id;
    }

    public function setEntityId(int $reservation_id): static
    {
        $this->entity_id = $reservation_id;
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

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getEntityType(): ?string
    {
        return $this->entity_type;
    }


    public function setEntityType(string $entity_type): static
    {
        $this->entity_type = $entity_type;

        return $this;
    }
}
