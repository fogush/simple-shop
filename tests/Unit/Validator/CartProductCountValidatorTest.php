<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Entity\CartProduct;
use App\Validator\CartProductCount;
use App\Validator\CartProductCountValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CartProductCountValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): CartProductCountValidator
    {
        return new CartProductCountValidator();
    }

    public function testCannotUseOtherConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate(new ArrayCollection(), new NotBlank());
    }

    public function testCanOnlyValidateCollection(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $this->validator->validate([], new CartProductCount());
    }

    public function testEmptyCartIsValid(): void
    {
        $this->validator->validate(new ArrayCollection(), new CartProductCount());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider provideCartProductsToCheckUniqueProductCount
     */
    public function testUniqueProductCount(ArrayCollection $cartProducts, bool $shouldFail): void
    {
        $this->validator->validate($cartProducts, new CartProductCount());

        if ($shouldFail) {
            $this->buildViolation('A cart must contain no more than 3 different products')->assertRaised();
        } else {
            $this->assertNoViolation();
        }
    }

    public function provideCartProductsToCheckUniqueProductCount(): array
    {
        return [
            'less than limit' => [$this->generateCartProducts(2), false],
            'exact as limit' => [$this->generateCartProducts(3), false],
            'above limit' => [$this->generateCartProducts(5), true],
        ];
    }

    /**
     * @dataProvider provideCartProductsToCheckProductItemCount
     */
    public function testProductItemCount(ArrayCollection $cartProducts, bool $shouldFail): void
    {
        $this->validator->validate($cartProducts, new CartProductCount());

        if ($shouldFail) {
            $this->buildViolation('A cart must contain no more than 10 items of a product')->assertRaised();
        } else {
            $this->assertNoViolation();
        }
    }

    public function provideCartProductsToCheckProductItemCount(): array
    {
        return [
            'less than limit' => [$this->generateCartProducts(1, 5), false],
            'exact as limit' => [$this->generateCartProducts(1, 10), false],
            'above limit' => [$this->generateCartProducts(1, 11), true],
        ];
    }

    private function generateCartProducts(int $uniqueProductCount, int $productItemCount = 1): ArrayCollection
    {
        $cartProducts = new ArrayCollection();
        for ($i = 0; $i < $uniqueProductCount; ++$i) {
            $cartProduct = new CartProduct();
            $cartProduct->setCount($productItemCount);

            $cartProducts->add($cartProduct);
        }

        return $cartProducts;
    }
}
