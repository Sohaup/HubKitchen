<?php

namespace PostApi\shared\helpers\queryBuilder\Interepter\Conditions;

class ComplexCondition extends ConditionUnit
{ 

    public function __construct(public ConditionsCollection $conditions)
    {
        $this->parseCondition($conditions);
    }

    public function addCondtion(ConditionUnit $condition)
    {
        $this->conditions[] = $condition;
    }

    public function parseCondition()
    {
        foreach ($this->conditions as $index => $condition) {
            $condition = clone $condition;
            $this->condition .= " {$condition->conditionObj->type->value} ";
            $this->condition .=  $condition->setCondition($condition->conditionObj);
            $this->addValue($condition->conditionObj->value);
        }
    }

   
}
