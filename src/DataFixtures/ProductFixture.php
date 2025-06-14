<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $categories = [];

        for ($i = 0; $i < 10; $i++) {
            $category = new \App\Entity\Category();
            $category->setName($faker->word);
            $manager->persist($category);
            $categories[] = $category;
        }


        for ($i = 0; $i < 100; $i++) {


            $product = new \App\Entity\Product();
            $product->setName($faker->word);
            $product->setDescription($faker->sentence);
            $product->setPrice($faker->randomFloat(2, 1, 1000));
            $product->setStockQuantity($faker->numberBetween(0, 100));
            $product->setSKU($faker->unique()->word);
            $product->setCategoryId($faker->numberBetween(1, 10));
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());
            $product->setWearhouseId($faker->numberBetween(1, 10));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
