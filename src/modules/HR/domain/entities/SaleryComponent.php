<?php

namespace PostApi\modules\HR\domain\entities;

use PostApi\modules\HR\helpers\types\CalcType;
use PostApi\modules\HR\helpers\types\PayElementsType;

class SaleryComponent
{
    private int $id;
    private string $name;
    private PayElementsType $type;
    private CalcType $calcType;

    public function __construct(?int $id = null, string $name, PayElementsType $type, CalcType $calcType)
    {
        $this->id = $id ?? 0;
        $this->name = $name;
        $this->type = $type;
        $this->calcType = $calcType;
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
    public function setType(PayElementsType $type)
    {
        $this->type = $type;
    }
    public function getType()
    {
        return $this->type;
    }
    public function setCalcType(CalcType $calcType)
    {
        $this->calcType = $calcType;
    }
    public function getCalcType()
    {
        return $this->calcType;
    }
}
