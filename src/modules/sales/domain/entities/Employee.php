<?php

namespace PostApi\modules\sales\domain\entities;

use PostApi\modules\auth\domain\Entities\User;

class Employee
{
    private string $id;
    private string $userId;
    private string $country;

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
    public function setCountry(string $country)
    {
        $this->country = $country;
    }
    public function getCountry()
    {
        return $this->country;
    }
}
