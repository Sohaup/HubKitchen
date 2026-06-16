<?php

namespace PostApi\modules\HR\domain\entities;

use PostApi\modules\HR\domain\aggregators\Applications;

class Job
{
    private ?int $id = 0;
    private string $title = "";
    private Department $department;
    private Applications $applications;

    public function __construct()
    {
        $this->applications = new Applications();
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    public function getTitle()
    {
        return $this->title;
    }

    public function setDepartment(Department $department)
    {
        $this->department = $department;
    }
    public function getDepartment()
    {
        return $this->department;
    }

    public function addApllication(Application $application) {
        $this->applications->addApplication($application);
    }
    public function removeApplication(Application $application) {
        $this->applications->deleteApplication($application);
    }
    public function getApplications() {
        return $this->applications;
    }
}
