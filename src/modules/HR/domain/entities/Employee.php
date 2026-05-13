<?php

namespace PostApi\modules\HR\domain\entities;

use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\HR\helpers\types\EmployeeStatusType;
use PostApi\modules\HR\helpers\types\MartialStatusType;

class Employee
{
    private ?string $id = "";
    private EmployeeStatusType $employeeStatus;
    private MartialStatusType $martialStatus;
    private User $user;
    private JobDescription $job;
    private User $manager;
    private string $employeedAt;
    private Department $department;
    private Addresse $addresse;
    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setEmployeeStatus(EmployeeStatusType $employeeStatus)
    {
        $this->employeeStatus = $employeeStatus;
    }
    public function getEmployeeStatus()
    {
        return $this->employeeStatus;
    }
    public function setMartialStatus(MartialStatusType $martialStatus)
    {
        $this->martialStatus = $martialStatus;
    }
    public function getMartialStatus()
    {
        return $this->martialStatus;
    }
    public function setEmployeedAt(string $employeedAt)
    {
        $this->employeedAt = $employeedAt;
    }
    public function getEmployeedAt()
    {
        return $this->employeedAt;
    }
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    public function getUser()
    {
        return $this->user;
    }
    public function setManager(User $manager)
    {
        $this->manager = $manager;
    }
    public function getManager()
    {
        return $this->manager;
    }
    public function setJob(JobDescription $job)
    {
        $this->job = $job;
    }
    public function getJob()
    {
        return $this->job;
    }
    public function setAddress(Addresse $addresse)
    {
        $this->addresse = $addresse;
    }
    public function getAddress()
    {
        return $this->addresse;
    }
    public function setDepartment(Department $department)
    {
        $this->department = $department;
    }
    public function getDepartment()
    {
        return $this->department;
    }
}
