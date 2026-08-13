<?php

namespace PostApi\modules\HR\domain\entities;

use PostApi\modules\HR\helpers\types\EmployeeStatusType;
use PostApi\modules\HR\helpers\types\MartialStatusType;

class Employee
{
    private ?string $id = "";
    private string $employeeStatus = "";
    private string $martialStatus = "";
    private string $userId = "";
    private JobDescription $job;
    private string $managerId = "";
    private string $employeedAt = "";
    private Department $department;
    private Addresse $addresse;

    public function __construct()
    {
        $this->job = new JobDescription();
        $this->department = new Department();
        $this->addresse = new Addresse();
    }

    public function create(string $id, string $employeeStatus, string $martialStatus, string $userId, JobDescription $job, string $managerId, string $employeedAt, Department $department, Addresse $addresse)
    {
        $this->id = $id;
        $this->employeeStatus = $employeeStatus;
        $this->martialStatus = $martialStatus;
        $this->userId = $userId;
        $this->job = $job;
        $this->managerId = $managerId;
        $this->employeedAt = $employeedAt;
        $this->department = $department;
        $this->addresse = $addresse;
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
    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }
    public function getUserId()
    {
        return $this->userId;
    }
    public function setManagerId(string $managerId)
    {
        $this->managerId = $managerId;
    }
    public function getManagerId()
    {
        return $this->managerId;
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
