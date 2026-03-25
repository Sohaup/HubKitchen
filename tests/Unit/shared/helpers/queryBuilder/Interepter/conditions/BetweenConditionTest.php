<?php

use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BetweenCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;

test("test condition", function () {
    $condition = new BetweenCondition(new Condition("id", ConditionOperators::BETWEEN, [4 , 5]));
    expect($condition->getCondition())->toBe(' WHERE  (id BETWEEN ? AND ?) ');
});


test("test basic condition value", function () {
    $condition = new BetweenCondition(new Condition("id", ConditionOperators::BETWEEN, [4,5]));
    expect($condition->getValues())->toBe([4,5]);
});
