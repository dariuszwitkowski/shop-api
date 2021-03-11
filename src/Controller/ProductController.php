<?php

namespace App\Controller;

use App\Exception\EntityNotFountException;
use App\Exception\InvalidFormException;
use App\Exception\InvalidQueryException;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ProductService $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/api/product/add", name="product_add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addProduct(Request $request): JsonResponse
    {
        try {
            $product = $this->productService->addProduct($request->request->all());
        } catch (InvalidFormException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }

        return $this->json($product->getId(), Response::HTTP_CREATED);
    }
    /**
     * @Route("/api/product/list", name="product_list", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function productList(Request $request): JsonResponse {
        $options = $request->query->all();
        try {
            $products = $this->productService->getProducts($options);
        } catch (InvalidQueryException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json($products);
    }

    /**
     * @Route("/api/product/update", name="product_update", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProduct(Request $request): JsonResponse
    {
        try {
            $product = $this->productService->updateProduct($request->request->all());
        } catch (InvalidFormException | EntityNotFountException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }

        return $this->json($product->getId());
    }

    /**
     * @Route("/api/product/remove", name="product_remove", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     */
    public function removeProduct(Request $request): JsonResponse {
        try {
            $this->productService->removeProduct((int)$request->request->get('id'));
        } catch (EntityNotFountException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }

        return $this->json("Product successfully deleted");
    }

}
