<?php

namespace PostApi\modules\sales\domain\entities;

class Review
{
    private int $id;
    private Customer $customer;
    private Product $product;
    private int $review;

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setCustomer(Customer $customer) {
        $this->customer = $customer;
    }
    public function getCustomer() {
        return $this->customer;
    }
    public function setProduct(Product $product) {
        $this->product = $product;
    }
    public function getProduct() {
        return $this->product;
    }
    public function setReview(int $review) {
        $this->review = $review;
    }
    public function getReview() {
        return $this->review;
    }
}
