<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Conditions;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;

class BetweenCondition extends ConditionUnit
{
    public function __construct(public Condition $conditionObj)
    {        
        $this->setCondition($this->conditionObj);
        $this->addValue($this->conditionObj->value);
    }   
    public function setCondition(Condition $condition)
    {       
        $marks = array_fill(0, count($condition->value), "?");
        $marksStr = implode(" AND " , $marks);
        $stringPart = " ({$condition->column} {$condition->operator->value} $marksStr) ";        
        $this->condition .= $stringPart;      
        return $stringPart;  
    }
}
