<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CartRepository;
use App\Validator\CartProductCount;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToMany(targetEntity=CartProduct::class, mappedBy="cart", orphanRemoval=true, cascade={"persist"})
     */
    #[CartProductCount]
    private Collection $cartProducts;

    public function __construct()
    {
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|CartProduct[]
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(CartProduct $cartProduct): void
    {
        if (!$this->cartProducts->contains($cartProduct)) {
            $this->cartProducts[] = $cartProduct;
            $cartProduct->setCart($this);
        }
    }

    public function removeCartProduct(CartProduct $cartProduct): void
    {
        if ($this->cartProducts->removeElement($cartProduct)) {
            // set the owning side to null (unless already changed)
            if ($cartProduct->getCart() === $this) {
                $cartProduct->setCart(null);
            }
        }
    }

    public function addProduct(Product $product, int $count): CartProduct
    {
        foreach ($this->cartProducts as $cartProduct) {
            if ($cartProduct->getProduct()->getId() === $product->getId()) {
                $cartProduct->setCount($count);

                return $cartProduct;
            }
        }

        $cartProduct = new CartProduct();
        $cartProduct->setProduct($product);
        $cartProduct->setCount($count);

        $this->addCartProduct($cartProduct);

        return $cartProduct;
    }

    public function removeProduct(Product $product): void
    {
        foreach ($this->cartProducts as $cartProduct) {
            if ($cartProduct->getProduct()->getId() === $product->getId()) {
                $this->removeCartProduct($cartProduct);
            }
        }
    }
}
