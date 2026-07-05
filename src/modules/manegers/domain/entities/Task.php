<?php

namespace PostApi\modules\manegers\domain\entities;

class Task
{
    private ?int $id = 0;
    private string $name = "";
    private string $description = "";
    private Maneger $maneger;
    private Department $department;

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setManeger(Maneger $maneger)
    {
        $this->maneger = $maneger;
    }
    public function getManeger()
    {
        return $this->maneger;
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
