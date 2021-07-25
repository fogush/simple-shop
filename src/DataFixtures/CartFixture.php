<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class CartFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $fixtures = Yaml::parse(file_get_contents(__DIR__ . '/Resources/carts.yaml'));
        foreach ($fixtures['carts'] as $cartFixture) {
            $cart = new Cart();

            foreach ($cartFixture['products'] as $cartProductFixture) {
                /** @var Product $product */
                $product = $this->getReference('product-' . $cartProductFixture['name']);

                $cart->addProduct($product, $cartProductFixture['count']);
            }

            $manager->persist($cart);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixture::class,
        ];
    }
}
