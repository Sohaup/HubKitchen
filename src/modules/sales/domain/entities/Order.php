<?php

namespace PostApi\modules\sales\domain\entities;

use DateTime;

class Order
{
    private string $id;
    private Customer $customer;
    private Cart $cart;
    private string $createdAt;

    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }
    public function getCustomer()
    {
        return $this->customer;
    }
    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }
    public function getCart()
    {
        return $this->cart;
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
