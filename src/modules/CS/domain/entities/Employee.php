<?php

namespace PostApi\modules\CS\domain\entities;

class Employee
{
    private ?string $id = "";
    private string $userId = "";
    private string $employeeId = "";
    private Role $role;

    public function __construct()
    {
        $this->role = new Role();
    }

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
    public function setEmployeeId(string $employeeId)
    {
        $this->employeeId = $employeeId;
    }
    public function getEmployeeId()
    {
        return $this->employeeId;
    }
    public function setRole(Role $role)
    {
        $this->role = $role;
    }
    public function getRole()
    {
        return $this->role;
    }
}
