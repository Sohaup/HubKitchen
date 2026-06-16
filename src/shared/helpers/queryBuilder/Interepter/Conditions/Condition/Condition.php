<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition;

class Condition
{
    public function __construct(public string $column, public ConditionOperators $operator, public mixed $value , public ?ConditionTypes $type=ConditionTypes::NULL) {}
    
}