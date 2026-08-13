<?php
namespace PostApi\modules\CS\domain\entities;

class Customer {
    private ?string $id;
    private string $userId;
    private string $country;

    public function getId() {
        return $this->id;
    }
    public function setId(string $id) {
        $this->id = $id;
    }
    public function setUserId(string $userId) {
        $this->userId = $userId;
    }
    public function getUserId() {
        return $this->userId;
    }
    public function setCountry(string $country) {
        $this->country = $country;
    }
    
    public function getCountry() {
        return $this->country;
    }
}