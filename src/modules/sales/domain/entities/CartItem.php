<?php

namespace PostApi\modules\sales\domain\entities;



class CartItem
{
    private int $id;
    private Cart $cart;
    private Product $product;
    private string $quantity;
    private string $addedAt;

    public function __construct()
    {
       $this->cart = new Cart();
    }

      public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setCart(Cart $cart) {
        $this->cart = $cart;
    }
    public function getCart() {
        return $this->cart;
    }
    public function setQuantity(string $quantity) {
        $this->quantity = $quantity;
    }
    public function getQuantity() {
        return $this->quantity;
    }
    public function setProduct(Product $product) {
        $this->product = $product;
    }
    public function getProduct() {
        return $this->product;
    }
    public function setAddedAt(string $addedAt) {
        $this->addedAt = $addedAt;
    }
    public function getAddedAt() {
        return $this->addedAt;
    }
}
