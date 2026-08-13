<?php

namespace PostApi\modules\sales\domain\entities;

use PostApi\modules\auth\domain\Entities\User;

class Customer
{
    private string $id;
    private string $userId;
    private string $stripeId;

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
    public function setStripeId(string $stripeId)
    {
        $this->stripeId = $stripeId;
    }

    public function getStripeId()
    {
        return $this->stripeId;
    }
}
