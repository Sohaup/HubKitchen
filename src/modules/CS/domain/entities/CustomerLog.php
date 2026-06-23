<?php

namespace PostApi\modules\CS\domain\entities;

use PostApi\modules\CS\domain\valueObjects\types\CustomerLogType;

class CustomerLog
{
    private ?int $id = 0;
    private Customer $customer;
    private string $logType;
    private string $created_at;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setLogType(string $logType)
    {
        foreach (CustomerLogType::cases() as $case) {
            if ($logType == $case->value) {
                $this->logType = $logType;
            }
        }
    }

    public function getLogType()
    {
        return $this->logType;
    }

    public function setCreatedAt(string $created_at)
    {
        $this->created_at = $created_at;
    }
    
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
