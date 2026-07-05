<?php

namespace PostApi\modules\manegers\domain\entities;

use PostApi\modules\manegers\domain\valueObjects\plan\PlansType;

class Plan
{
    private ?int $id = 0;
    private string $type = "";
    private string $name = "";
    private string $description = "";
    private Maneger $maneger;

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setType(string $type)
    {       
        foreach (PlansType::cases() as $case) {
            if ($case->value == $type) {
                $this->type = $type;
            }
        }
    }
    public function getType()
    {
        return $this->type;
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
}
