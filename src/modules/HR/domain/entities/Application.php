<?php

namespace PostApi\modules\HR\domain\entities;

class Application
{
    private ?int $id = 0;
    private string $name = "";
    private string $email = "";
    private string $phone = "";
    private string $cv = "";
    private string $skills = "";

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
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function setCv(string $cv)
    {
        $this->cv = $cv;
    }
    public function getCv()
    {
        return $this->cv;
    }
    public function setSkills(string $skills)
    {
        $this->skills = $skills;
    }
    public function getSkills()
    {
        return $this->skills;
    }
}
