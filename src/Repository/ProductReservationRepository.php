<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductReservation>
 */
class ProductReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductReservation::class);
    }

    public function create(Product $product, int $quantity, int $warehouse_id, ?string $comment): ProductReservation
    {
        return  (new ProductReservation())
            ->setProductId($product->getId())
            ->setQuantity($quantity)
            ->setReservedAt(new \DateTimeImmutable())
            ->setComment($comment)
            ->setWarehouseId($warehouse_id);
    }


    public function findByProductId(int $productId): array
    {
        return $this->createQueryBuilder('pr')
            ->andWhere('pr.product_id = :productId')
            ->setParameter('productId', $productId)
            ->orderBy('pr.reserved_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function save(ProductReservation $reservation): void
    {
        $this->getEntityManager()->persist($reservation);
        $this->getEntityManager()->flush();
    }

    public function remove(ProductReservation $reservation): void
    {
        $this->getEntityManager()->remove($reservation);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return ProductReservation[] Returns an array of ProductReservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProductReservation
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
