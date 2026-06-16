<?php

namespace PostApi\modules\HR\domain\entities;

use PostApi\modules\HR\helpers\types\CalcType;
use PostApi\modules\HR\helpers\types\PayElementsType;

class SaleryComponent 
{
    private int $id = 0;
    private string $name = "";
    private string $type = "";
    private string $calcType = "";

    public function __construct(?int $id = null, string $name, string $type, string $calcType)
    {
        $this->id = $id ?? 0;
        $this->name = $name;
        $this->setType($type);
        $this->setCalcType($calcType);
    }

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
    public function setType(string $type)
    {
        foreach (PayElementsType::cases() as $case) {
            if ($case->value === $type) {
                $this->type = $case->value;
                break;
            }
        }
        $this->type = $type;
    }
    public function getType()
    {
        return $this->type;
    }
    public function setCalcType(string $calcType)
    {
        foreach (CalcType::cases() as $case) {
            if ($case->value === $calcType) {
                $this->calcType = $case->value;
                break;
            }
        }
    }
    public function getCalcType()
    {
        return $this->calcType;
    }  
}
