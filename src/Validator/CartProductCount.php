<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CartProductCount extends Constraint
{
    public const MAX_UNIQUE_PRODUCTS = 3;
    public const MAX_PRODUCT_ITEMS = 10;

    public string $maxUniqueProductsMessage = 'A cart must contain no more than '
        . self::MAX_UNIQUE_PRODUCTS . ' different products';
    public string $maxProductItemsMessage = 'A cart must contain no more than '
        . self::MAX_PRODUCT_ITEMS . ' items of a product';
}
