<?php

namespace PostApi\modules\HR\domain\entities;

use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\HR\helpers\types\EmployeeStatusType;
use PostApi\modules\HR\helpers\types\MartialStatusType;

class Employee
{
    private ?string $id = "";
    private string $employeeStatus = "";
    private string $martialStatus = "";
    private User $user;
    private JobDescription $job;
    private User $manager;
    private string $employeedAt = "";
    private Department $department;
    private Addresse $addresse;

    public function __construct()
    {
        $this->user = new User();
        $this->job = new JobDescription();
        $this->manager = new User();
        $this->department = new Department();
        $this->addresse = new Addresse();
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setEmployeeStatus(string $employeeStatus)
    {
        foreach (EmployeeStatusType::cases() as $employeeStatusType) {
            if ($employeeStatusType->value === $employeeStatus) {
                $this->employeeStatus = $employeeStatus;
                return;
            }
        }
    }
    public function getEmployeeStatus()
    {
        return $this->employeeStatus;
    }
    public function setMartialStatus(string $martialStatus)
    {
        foreach (MartialStatusType::cases() as $martialStatusType) {
            if ($martialStatusType->value === $martialStatus) {
                $this->martialStatus = $martialStatus;
                return;
            }
        }
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
