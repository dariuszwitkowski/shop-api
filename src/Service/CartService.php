<?php


namespace App\Service;


use App\Exception\CartExistenceException;
use App\Model\Cart;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private SessionInterface $session;
    private ProductService $productService;
    public function __construct(SessionInterface $session, ProductService $productService)
    {
        $this->session = $session;
        $this->productService = $productService;
    }
    public function createCart() {
        $this->getCartCheck(false);
        $this->session->set("cart", new Cart());
    }
    public function getProductsFromCart():ArrayCollection {
        return $this->getCartCheck(false)->getProducts();
    }
    public function removeItemFromCart(int $id) {
        $cart = $this->getCartCheck(true);
        $product = $this->productService->findProduct($id);
        $cart->removeProduct($product);
    }
    public function addItemToCart(int $id) {
        $cart = $this->getCartCheck(true);
        $product = $this->productService->findProduct($id);
        $cart->addProduct($product);
    }
    private function getCartCheck(bool $shouldExist): Cart {
        $cart = $this->session->get("cart", false);
        if(!($cart ^ $shouldExist))
            throw new CartExistenceException($shouldExist);
        return $cart;
    }
}
