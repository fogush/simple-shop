<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CartHandler
{
    public function __construct(
        private CartRepository $cartRepository,
        private ValidatorInterface $validator
    ) {
    }

    public function handle(Cart $cart, Product $product, Request $request): ?string
    {
        $count = $request->query->getInt('count', 1);
        if (0 === $count) {
            $cart->removeProduct($product);
        } else {
            $cart->addProduct($product, $count);

            $errors = $this->validator->validate($cart);

            if (count($errors) > 0) {
                return (string) $errors;
            }
        }

        $this->cartRepository->save($cart);

        return null;
    }
}
