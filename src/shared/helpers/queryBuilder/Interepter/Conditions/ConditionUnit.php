<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Conditions;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;

abstract class ConditionUnit
{
    protected string $condition = " WHERE ";
    protected array $values = [];
    public Condition $conditionObj;
    public function getCondition()
    {
        return $this->condition;
    }
    public function setCondition(Condition $condition)
    {
        $marks = [];
        $stringPart = "";
        if (is_array($condition->value)) {
            $marks = array_fill(0, count($condition->value), "?");
            $marksStr = implode(",", $marks);
            $stringPart = " ({$condition->column} {$condition->operator->value} ($marksStr)) ";
            $this->condition .= $stringPart;
        } else {
            $stringPart = " ({$condition->column} {$condition->operator->value} ?) ";
            $this->condition .= $stringPart;
        }
        return $stringPart;
    }
    public function addValue(mixed $value)
    {
        $this->values[] = $value;
    }
    public function getValues()
    {
        $combinedValues = [];
        foreach ($this->values as $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $combinedValues[] = $item;
                }
            } else {
                $combinedValues[] = $value;
            }
        }
        return $combinedValues;
    }
}
