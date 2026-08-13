<?php

namespace PostApi\modules\sales\domain\entities;

use PostApi\modules\HR\helpers\types\PayElementsType;
use PostApi\modules\sales\domain\valueObjects\types\payment\PaymentStatusType;

class Payment
{
    private int $id;
    private string $amount;
    private string $currency;
    private string $status;
    private string $stripeSessionId;
    private string $stripePaymentIntentId;
    private Order $order;
    private string $createdAt;
    private string $updatedAt;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAmount(string $amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setStatus(string $status)
    {
        foreach (PaymentStatusType::cases() as $case) {            
            if ($case->value == $status) {
                $this->status = $status;
            }
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStripeSessionId(string $stripeSessionId)
    {
        return $this->stripeSessionId = $stripeSessionId;
    }

    public function getStripeSessionId()
    {
        return $this->stripeSessionId;
    }

    public function setStripePaymentIntentId(string $stripePaymentIntentId)
    {
        $this->stripePaymentIntentId = $stripePaymentIntentId;
    }

    public function getStripePaymentIntentId()
    {
        return $this->stripePaymentIntentId;
    }

    public function setOrder(Order $order)
    {
        $this->order = $order;
    }
    public function getOrder()
    {
        return $this->order;
    }
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
