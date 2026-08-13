<?php

namespace PostApi\modules\sales\domain\entities;

use DateTime;
use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\sales\domain\aggregators\CartItems;

class Cart
{
    private string $id = "";
    private string $userId = "";   
    private CartItems $cartItems;
    private string $price = "";
    private string $createdAt = "";

    public function __construct()
    {
        $this->cartItems = new CartItems();
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }
    public function getUserId()
    {
        return $this->userId;
    }   
    public function addCartItem(CartItem $cartItem)
    {
        $this->cartItems->addCartItem($cartItem);
    }
    public function removeCartItem(CartItem $cartItem)
    {
        $this->cartItems->removeCartItem($cartItem);
    }   
    public function getCartItems() : CartItems
    {
        return $this->cartItems;
    }
    public function setPrice(float $price)
    {
        $this->price = $price;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
