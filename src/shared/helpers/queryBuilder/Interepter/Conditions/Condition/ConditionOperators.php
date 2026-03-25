<?php

namespace PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition;

enum ConditionOperators: string
{
    case LESSTHAN = "<";
    case MORETHAN = ">";
    case EQUAL = "=";
    case NOTEQUAL = "!=";
    case LESSTHANOREQUAL = "<=";
    case MORETHANOREQUAL = ">=";
    case IN = "IN";
    case NOTIN = "NOT IN";
    case BETWEEN = "BETWEEN";
    case NOTBETWEEN = "NOT BETWEEN";
}
