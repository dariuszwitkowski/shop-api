<?php


namespace App\Model;


use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Collection;

class Cart
{
    private ArrayCollection $products;

    public function getProducts(): ArrayCollection {
        return $this->products;
    }
    public function addProduct(Product $product) {
        $this->products->add($product);
    }
    public function removeProduct(Product $product) {
        if($this->products->contains($product)) {
            $this->products->removeElement($product);
        }
    }
}
