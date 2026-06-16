<?php

namespace PostApi\modules\HR\domain\entities;

class Salery
{
    private int $id  = 0;
    private Employee $employee;
    private float $salery;

    public function __construct(?int $id = null, Employee $employee, float $salery)
    {
        $this->id = $id ?? 0;
        $this->employee = $employee;
        $this->salery = $salery;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId(int $id) {
        $this->id = $id;
    }
    public function setEmployee(Employee $employee) {
        $this->employee = $employee;
    }
    public function getEmployee() {
        return $this->employee;
    }
    public function setSalery(float $salery) {
        $this->salery = $salery;
    }
    public function getSalery() {
        return $this->salery;
    }
}
