<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Conditions;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;

class BasicCondition extends ConditionUnit
{
    public function __construct(public Condition $conditionObj)
    {        
        $this->setCondition($this->conditionObj);
        $this->addValue($this->conditionObj->value);
    }    
   
}