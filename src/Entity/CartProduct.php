<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CartProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Product $product;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="cartProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Cart $cart;

    /**
     * @ORM\Column(type="integer")
     */
    private int $count = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }
}
