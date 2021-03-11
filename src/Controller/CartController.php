<?php

namespace App\Controller;

use App\Exception\CartDoesNotExistException;
use App\Exception\EntityNotFountException;
use App\Exception\GuestExistenceException;
use App\Exception\InvalidQueryException;
use App\Exception\QuantityTooBigException;
use App\Exception\TooManyProductsInCartException;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class CartController extends AbstractController
{
    private CartService $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Create new Cart for specified user
     * @Route("/api/cart/create", name="cart_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createCart(Request $request): JsonResponse
    {
        $guestHash = $request->request->get("guestHash");

        try {
            $this->cartService->validateCartInput($guestHash);
        } catch (InvalidQueryException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        try {
            $data = $this->cartService->createCart($guestHash);
        } catch (GuestExistenceException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json($data['cart']->getId(), $data['code']);
    }
    /**
     * @Route("/api/cart/add_item", name="cart_add_item", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addItemToCart(Request $request):JsonResponse {
        $productId = $request->request->get("productId");
        $guestHash = $request->request->get("guestHash");
        try {
            $this->cartService->validateCartInput($productId, $guestHash);
        } catch (InvalidQueryException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        try {
            $this->cartService->addItemToCart((int)$productId, $guestHash);
        } catch (EntityNotFountException | QuantityTooBigException | GuestExistenceException | TooManyProductsInCartException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("Success");
    }
    /**
     * @Route("/api/cart/remove_item", name="remove_item", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     */
    public function removeItemFromCart(Request $request):JsonResponse {
        $productId = $request->request->get("productId");
        $guestHash = $request->request->get("guestHash");
        try {
            $this->cartService->validateCartInput($productId, $guestHash);
        } catch (InvalidQueryException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }

        try {
            $this->cartService->removeItemFromCart((int)$productId, $guestHash);
        } catch (EntityNotFountException | GuestExistenceException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("Success");
    }
    /**
     * @Route("/api/cart/get_products", name="cart_get_products", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getProductsFromCart(Request $request):JsonResponse {
        $guestHash = $request->query->get("guestHash");
        try {
            $this->cartService->validateCartInput($guestHash);
        } catch (InvalidQueryException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }

        try {
            $data = $this->cartService->getItemsFromCart($guestHash);
        } catch (GuestExistenceException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json($data);
    }
}
