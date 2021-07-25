<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormFactoryInterface;

class ProductHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private FormFactoryInterface $formFactory
    ) {
    }

    public function handle(array $json, Product $product): ?string
    {
        $form = $this->formFactory->create(ProductFormType::class, $product);
        $form->submit($json, false);

        if (!$form->isValid()) {
            return (string) $form->getErrors(true);
        }

        $this->productRepository->save($product);

        return null;
    }
}
