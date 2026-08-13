<?php

namespace PostApi\modules\sales\domain\entities;

class Offer
{
    private int $id;
    private Product $product;
    private string $value;

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
    public function getProduct()
    {
        return $this->product;
    }
    public function setValue(float $value)
    {
        $this->value = $value;
    }
    public function getValue()
    {
        return (float)$this->value;
    }
}
