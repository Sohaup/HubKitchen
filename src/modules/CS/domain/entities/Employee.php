<?php

namespace PostApi\modules\CS\domain\entities;

use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\HR\domain\entities\Employee as HrEmployee;

class Employee
{
    private ?string $id = "";
    private User $user;
    private HrEmployee $employee;
    private Role $role;

    public function __construct()
    {
        $this->user = new User();
        $this->employee = new HrEmployee();
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
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    public function getUser()
    {
        return $this->user;
    }
    public function setEmployee(HrEmployee $employee)
    {        
        $this->employee = $employee;
    }
    public function getEmployee()
    {        
        return $this->employee;
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
