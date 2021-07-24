<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Form\Handler\CartHandler;
use App\Repository\CartRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/carts', name: 'app.cart.')]
class CartController extends AbstractController
{
    #[Route('/{id}', name: 'info', methods: ['GET'])]
    public function info(Cart $cart): Response
    {
        $products = [];
        $totalSum = 0;
        foreach ($cart->getCartProducts() as $cartProduct) {
            $product = $cartProduct->getProduct()->jsonSerialize();
            $product['count'] = $cartProduct->getCount();
            $totalSum += $cartProduct->getProduct()->getPriceCents();

            $products[] = $product;
        }

        return $this->json([
            'products' => $products,
            'totalSumDollars' => $totalSum / 100
        ], Response::HTTP_OK);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(CartRepository $cartRepository): Response
    {
        $cart = new Cart();
        $cartRepository->save($cart);

        return $this->json(['id' => $cart->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id<\d+>}/products/{productId<\d+>}', name: 'add_product', methods: ['POST'])]
    #[Entity('product', expr: 'repository.find(productId)')]
    public function addProduct(Cart $cart, Product $product, CartHandler $cartHandler, Request $request): Response
    {
        $errors = $cartHandler->handle($cart, $product, $request);
        if ($errors !== null) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['id' => $cart->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id<\d+>}/products/{productId<\d+>}', name: 'delete_product', methods: ['DELETE'])]
    #[Entity('product', expr: 'repository.find(productId)')]
    public function deleteProduct(Cart $cart, Product $product, CartRepository $cartRepository): Response
    {
        $cart->removeProduct($product);

        $cartRepository->save($cart);

        return new Response(status: Response::HTTP_OK);
    }
}
