<?php

namespace PostApi\modules\inovice\domain\entities;

use DateTime;

class Prucher
{
    private string $id;
    private float $quantity;
    private Supplier $supplier;
    private Product $product;
    private string $createdAt;

    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setQuantity(float $quantity)
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
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
    public function getProduct()
    {
        return $this->product;
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
