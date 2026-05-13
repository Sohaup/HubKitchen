<?php

namespace PostApi\modules\HR\domain\entities;

class PayrollJournal
{
    private int $id;
    private Employee $employee;
    private SaleryComponent $saleryComponent;
    private float $amount;
    private string $date;

    public function __construct(?int $id = null, Employee $employee, SaleryComponent $saleryComponent, float $amount, string $date)
    {
        $this->id = $id ?? 0;
        $this->employee = $employee;
        $this->saleryComponent = $saleryComponent;
        $this->amount = $amount;
        $this->date;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;
    }
    public function getEmployee()
    {
        return $this->employee;
    }

    public function setSaleryComponent(SaleryComponent $saleryComponent)
    {
        $this->saleryComponent = $saleryComponent;
    }
    public function getSaleeyComponent()
    {
        return $this->saleryComponent;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }
    public function getAmount()
    {
        return $this->amount;
    }
    
    public function setDate(string $date)
    {
        $this->date = $date;
    }
    public function getDate()
    {
        return $this->date;
    }
}
