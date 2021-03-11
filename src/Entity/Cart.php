<?php

namespace App\Entity;

use App\Exception\QuantityTooBigException;
use App\Exception\TooManyProductsInCartException;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    const MAX_PROD_COUNT = 3;
    const MAX_PROD_QUANTITY = 10;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=Guest::class, mappedBy="Cart", cascade={"persist", "remove"})
     */
    private ?Guest $guest;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="cart")
     */
    private $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuest(): ?Guest
    {
        return $this->guest;
    }

    public function setGuest(?Guest $guest): self
    {
        // unset the owning side of the relation if necessary
        if ($guest === null && $this->guest !== null) {
            $this->guest->setCart(null);
        }

        // set the owning side of the relation if necessary
        if ($guest !== null && $guest->getCart() !== $this) {
            $guest->setCart($this);
        }

        $this->guest = $guest;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if($this->cartItems->count() >= self::MAX_PROD_COUNT)
            throw new TooManyProductsInCartException();

        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems[] = $cartItem;
            $cartItem->setCart($this);
        } else {
            if($cartItem->getQuantity() >= self::MAX_PROD_QUANTITY)
                throw new QuantityTooBigException($cartItem->getProduct()->getName());
            $cartItem->setQuantity($cartItem->getQuantity()+1);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if($cartItem->getQuantity()>1) {
            $cartItem->setQuantity($cartItem->getQuantity()-1);
        } else {
            if ($this->cartItems->removeElement($cartItem)) {
                // set the owning side to null (unless already changed)
                if ($cartItem->getCart() === $this) {
                    $cartItem->setCart(null);
                }
            }
        }

        return $this;
    }
}
