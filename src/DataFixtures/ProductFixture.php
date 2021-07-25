<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fixtures = Yaml::parse(file_get_contents(__DIR__ . '/Resources/products.yaml'));
        foreach ($fixtures['products'] as $productFixture) {
            $product = new Product();
            $product->setName($productFixture['name']);
            $product->setTitle($productFixture['title']);
            $product->setPriceCents($productFixture['priceCents']);

            $manager->persist($product);
            $this->addReference('product-' . $productFixture['name'], $product);
        }

        $manager->flush();
    }
}
