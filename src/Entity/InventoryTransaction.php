<?php

namespace App\Entity;

use AllowDynamicProperties;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
#[AllowDynamicProperties] #[ApiResource]
#[ORM\Entity]
class InventoryTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $sourceType;

    #[ORM\Column]
    private int $sourceId;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Product $product;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?int $warehouse_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    public function __construct(
        ?int $id = null,
        int $sourceId = 0,
        Product $product = null,
        ?int $quantity = null,
        ?\DateTimeImmutable $created_at = null,
        ?int $warehouse_id = null,
        ?string $comment = null
    )
    {
        $this->product = $product;
        $this->id = $id;
        $this->sourceId = $sourceId;
        $this->quantity = $quantity;
        $this->created_at = $created_at ?? new \DateTimeImmutable();
        $this->warehouse_id = $warehouse_id;
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
