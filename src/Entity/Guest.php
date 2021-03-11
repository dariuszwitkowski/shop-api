<?php

namespace App\Entity;

use App\Repository\GuestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuestRepository::class)
 */
class Guest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $hash;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private ?string $ip;

    /**
     * @ORM\OneToOne(targetEntity=Cart::class, inversedBy="guest", cascade={"persist", "remove"})
     */
    private ?Cart $Cart;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->Cart;
    }

    public function setCart(?Cart $Cart): self
    {
        $this->Cart = $Cart;

        return $this;
    }
}
