<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\Handler\ProductHandler;
use App\Helper\JsonHelper;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'app.product.')]
class ProductController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));

        $paginator = $productRepository->getPaginator($offset);

        //TODO: it could be better to do not show the links when a page is not available
        return $this->json([
            'products' => $paginator->getIterator()->getArrayCopy(),
            'previousPage' => $this->getLinkToPage(max(0, $offset - ProductRepository::PRODUCTS_PER_PAGE)),
            'nextPage' => $this->getLinkToPage(min(count($paginator), $offset + ProductRepository::PRODUCTS_PER_PAGE)),
        ]);
    }

    private function getLinkToPage(int $offset): string
    {
        return $this->generateUrl('app.product.list', ['offset' => $offset]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(ProductHandler $handler, Request $request): Response
    {
        if (($json = JsonHelper::decode($request->getContent())) === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $product = new Product();

        $errors = $handler->handle($json, $product);
        if (null !== $errors) {
            return $this->json(['error' => 'Provided product is not valid: ' . $errors], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['id' => $product->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    public function update(Product $product, ProductHandler $handler, Request $request): Response
    {
        if (($json = JsonHelper::decode($request->getContent())) === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $errors = $handler->handle($json, $product);
        if (null !== $errors) {
            return $this->json(['error' => 'Provided product is not valid: ' . $errors], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['id' => $product->getId()], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(Product $product, ProductRepository $repository): Response
    {
        $repository->delete($product);

        return new Response(status: Response::HTTP_OK);
    }
}
