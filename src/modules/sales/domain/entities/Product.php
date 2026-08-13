<?php

namespace PostApi\modules\sales\domain\entities;

use DateTime;

class Product
{
    private string $id;
    private string $name;
    private float $price;
    private ?string $stripId = null;
    private string $image;
    private string $props = "";
    private Category $category;
    private string $createdAt;

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
    public function setStripeId(string $stripId)
    {
        $this->stripId = $stripId;
    }
    public function getStripeId()
    {
        return $this->stripId;
    }
    public function setImage(string $image)
    {
        $this->image = $image;
    }
    public function getImage()
    {
        return $this->image;
    }
    public function setProps(string $props)
    {
        $this->props = $props;
    }
    public function getProps()
    {
        return $this->props;
    }
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }
    public function getCategory()
    {
        return $this->category;
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
