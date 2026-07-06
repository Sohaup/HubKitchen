<?php

namespace PostApi\modules\inovice\domain\entities;

use DateTime;

class Product
{
    private string $id;
    private string $name;
    private float $price;
    private int $quantity;
    private Supplier $supplier;
    private DateTime $createdAt;

    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setPrice(float $price)
    {
        $this->price = $price;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function setSupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }
    public function getSupplier()
    {
        return $this->supplier;
    }
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = new DateTime($createdAt);
    }
    public function getCreatedAt()
    {
        return $this->createdAt->format("r");
    }
}
