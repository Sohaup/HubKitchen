<?php
namespace PostApi\modules\CS\domain\entities;

use PostApi\modules\auth\domain\Entities\User;

class Customer {
    private ?string $id;
    private User $user;
    private string $country;

    public function getId() {
        return $this->id;
    }
    public function setId(string $id) {
        $this->id = $id;
    }
    public function setUser(User $user) {
        $this->user = $user;
    }
    public function getUser() {
        return $this->user;
    }
    public function setCountry(string $country) {
        $this->country = $country;
    }
    
    public function getCountry() {
        return $this->country;
    }
}