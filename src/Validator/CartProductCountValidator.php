<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\CartProduct;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CartProductCountValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CartProductCount) {
            throw new UnexpectedTypeException($constraint, CartProductCount::class);
        }

        if (!$value instanceof Collection) {
            throw new UnexpectedValueException($value, Collection::class);
        }

        if ($value->isEmpty()) {
            //A cart without products is still valid
            return;
        }

        if ($value->count() > CartProductCount::MAX_UNIQUE_PRODUCTS) {
            $this->context
                ->buildViolation($constraint->maxUniqueProductsMessage)
                ->addViolation()
            ;
        }

        /** @var CartProduct $cartProduct */
        foreach ($value as $cartProduct) {
            if ($cartProduct->getCount() > CartProductCount::MAX_PRODUCT_ITEMS) {
                $this->context
                    ->buildViolation($constraint->maxProductItemsMessage)
                    ->addViolation()
                ;
            }
        }
    }
}