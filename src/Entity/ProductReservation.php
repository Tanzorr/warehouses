<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

use App\Constants\ReservationStatusMessage;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            denormalizationContext: ['groups' => [self::GROUP_CREATE, ProductReservationItem::GROUP_CREATE]],
        ),
        new Put(
            denormalizationContext: ['groups' => [self::GROUP_UPDATE]],
        ),
        new Delete()
    ]
)]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\EntityListeners(['App\EventListener\ProductReservationListener'])]
class ProductReservation
{
    const GROUP_CREATE = 'product_reservation_create';
    const GROUP_UPDATE = 'product_reservation_update';

    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_COMMITTED = 'committed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(
        choices: [
            self::STATUS_CANCELED,
            self::STATUS_EXPIRED,
            self::STATUS_COMMITTED,
            self::STATUS_PENDING
        ],
        message: ReservationStatusMessage::ERROR_WRONG_STATUS
    )]
    #[Groups([self::GROUP_CREATE, self::GROUP_UPDATE])]
    public string $status = self::STATUS_PENDING;

    #[ORM\Column]
    private \DateTimeImmutable $reserved_at;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $released_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expired_at = null;

    #[ORM\Column(nullable: true)]
    #[Groups(self::GROUP_CREATE)]
    private ?string $comment = null;

    #[ORM\Column]
    private \DateTimeImmutable $created_at;

    #[ORM\Column]
    private \DateTimeImmutable $updated_at;

    #[ORM\OneToMany(
        targetEntity: ProductReservationItem::class,
        mappedBy: 'productReservation',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(self::GROUP_CREATE)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->reserved_at = new \DateTimeImmutable();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getReservedAt(): \DateTimeImmutable
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

    public function getExpiredAt(): ?\DateTimeImmutable
    {
        return $this->expired_at;
    }

    public function setExpiredAt(?\DateTimeImmutable $expired_at): self
    {
        $this->expired_at = $expired_at;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ProductReservationItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setProductReservation($this);
        }
        return $this;
    }

    public function removeItem(ProductReservationItem $item): self
    {
        if ($this->items->removeElement($item)) {
            if ($item->getProductReservation() === $this) {
                $item->setProductReservation(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->getComment() ?? 'Reservation ' . $this->getId() . ' at ' . $this->getReservedAt()->format('Y-m-d H:i:s');
    }
}
