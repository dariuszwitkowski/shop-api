<?php


namespace App\Service;


use App\Entity\CartItem;
use App\Entity\Cart;
use App\Entity\Product;
use App\Exception\EntityNotFountException;
use App\Exception\InvalidQueryException;
use App\Model\CartItemResponse;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
class CartService
{
    private ProductService $productService;
    private GuestService $guestService;
    private CartRepository $cartRepository;
    private EntityManagerInterface $em;
    private CartItemRepository $cartItemRepository;
    public function __construct(
        ProductService $productService,
        CartRepository $cartRepository,
        GuestService $guestService,
        EntityManagerInterface $em,
        CartItemRepository $cartItemRepository
    )
    {
        $this->productService = $productService;
        $this->guestService = $guestService;
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->em = $em;
    }
    public function createCart(string $hash): array {
        $cart = $this->getCart($hash);
        $code = Response::HTTP_CREATED;
        if(!$cart){
            $guest = $this->guestService->getGuest($hash);
            $cart = (new Cart())->setGuest($guest);
            $this->em->persist($cart);
            $this->em->flush();
            $code = Response::HTTP_OK;
        }
        return ['cart' => $cart , "code"=> $code ];
    }
    public function getItemsFromCart(string $hash):?array {
        $cart = $this->getCart($hash);

        if(!$cart)
            $cart = $this->createCart($hash)['cart'];
        $response = [];
        /** @var CartItem $cartItem */
        foreach ($cart->getCartItems() as $cartItem) {
            $response['products'][] =
                (new CartItemResponse())
                    ->setId($cartItem->getProduct()->getId())
                    ->setName($cartItem->getProduct()->getName())
                    ->setQuantity($cartItem->getQuantity())
                    ->setPrice($this->productService->transformPrice($cartItem->getProduct()->getPrice()));
        }
        $response['sum'] = $this->productService->transformPrice($this->calculatePriceSum($cart));
        return $response;
    }
    public function removeItemFromCart(int $productId, string $hash): void {
        $cart = $this->getCart($hash);

        if(!$cart)
            throw new EntityNotFountException();
        $product = $this->productService->findProduct($productId);
        $item = $this->getCartItem($product, $cart);
        if(!$item)
            throw new EntityNotFountException();
        $cart->removeCartItem($item);
        $this->em->persist($cart);
        $this->em->flush();
    }
    public function addItemToCart(int $productId, string $hash): void {
        $cart = $this->getCart($hash);
        if(!$cart)
            $cart = $this->createCart($hash)['cart'];

        $product = $this->productService->findProduct($productId);
        $item = $this->getCartItem($product, $cart);
        if(!$item)
            $item = $this->createCartItem($product);
        $cart->addCartItem($item);
        $this->em->persist($cart);
        $this->em->flush();
    }
    public function validateCartInput(...$fields) {
        foreach ($fields as $field) {
            if(!$field)
                throw new InvalidQueryException();
        }
    }
    private function getCart(string $hash): ?Cart {
        $guest = $this->guestService->getGuest($hash);
        return $guest->getCart();
    }
    private function getCartItem(Product $product, Cart $cart): ?CartItem {

        return $this->cartItemRepository->findOneBy(["product" => $product, "cart" => $cart]);
    }
    private function createCartItem(Product $product): CartItem {
        $item = (new CartItem())->setProduct($product);
        $this->em->persist($item);
        return $item;
    }
    private function calculatePriceSum(Cart $cart) {
        $sum = 0;
        /** @var CartItem $item */
        foreach ($cart->getCartItems() as $item) {
            $sum += $item->getProduct()->getPrice() * $item->getQuantity();
        }
        return $sum;
    }
}
