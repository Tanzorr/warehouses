<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Warehouse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $warehouses = $manager->getRepository(Warehouse::class)->findAll();
        $categories = $manager->getRepository(Category::class)->findAll();

        for ($i = 0; $i < 100; $i++) {
            $product = new Product();
            $product->setName($faker->word);
            $product->setDescription($faker->sentence);
            $product->setPrice($faker->randomFloat(2, 1, 1000));
            $product->setStockQuantity($faker->numberBetween(0, 100));
            $product->setSku($faker->unique()->word); // SKU (не all caps у методі)
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());

            $randomCategory = $categories[array_rand($categories)];
            $product->setCategoryId($randomCategory->getId());
            $randomWarehouse = $warehouses[array_rand($warehouses)];
            $product->setWearhouseId($randomWarehouse->getId());

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixture::class,
            WarehouseFixture::class,
        ];
    }
}

