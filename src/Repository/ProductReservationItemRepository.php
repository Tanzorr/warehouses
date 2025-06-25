<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductReservation;
use App\Entity\ProductReservationItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductReservationItem>
 */
class ProductReservationItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductReservationItem::class);
    }

    public function create(Product $product, ProductReservation $productReservation, int $amount): ProductReservationItem
    {
        return (new ProductReservationItem())
            ->setProductReservation($productReservation)
            ->setProduct($product)
            ->setAmount($amount);
    }

    public function save(ProductReservationItem $item): void
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return ProductReservationItem[] Returns an array of ProductReservationItem objects
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

    //    public function findOneBySomeField($value): ?ProductReservationItem
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
