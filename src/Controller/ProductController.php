<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\Handler\ProductHandler;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/products', name: 'app.product.')]
class ProductController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));

        $paginator = $productRepository->getPaginator($offset);

        return $this->json([
            'products' => $paginator->getIterator()->getArrayCopy(),
            'previousPage' => $this->getLinkToPage($offset - ProductRepository::PRODUCTS_PER_PAGE),
            'nextPage' => $this->getLinkToPage(min(count($paginator), $offset + ProductRepository::PRODUCTS_PER_PAGE)),
        ]);
    }

    private function getLinkToPage(int $offset): string
    {
        return $this->generateUrl('app.product.list', ['offset' => $offset], UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(ProductHandler $handler, Request $request): Response
    {
        if (($json = $this->getJson($request)) === null) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $product = new Product();

        if (!$handler->handle($json, $product)) {
            return $this->json(['error' => 'Provided product is not valid'], 400);
        }

        return $this->json(['id' => $product->getId()], 201);
    }

    #[Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(Product $product, ProductHandler $handler, Request $request): Response
    {
        if (($json = $this->getJson($request)) === null) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        if (!$handler->handle($json, $product)) {
            return $this->json(['error' => 'Provided product is not valid'], 400);
        }

        return $this->json(['id' => $product->getId()], 200);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(Product $product, ProductRepository $repository): Response
    {
        $repository->delete($product);

        return $this->json([], 200);
    }

    private function getJson(Request $request): ?array
    {
        try {
            return json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }
    }
}
