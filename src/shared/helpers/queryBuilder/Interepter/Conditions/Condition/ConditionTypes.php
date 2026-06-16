<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition;

enum ConditionTypes : string {
    case NULL = "";
    case OR = "OR";
    case AND = "AND";
}