<?php

use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BasicCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;

test("test condition", function () {
    $condition = new BasicCondition(new Condition("id", ConditionOperators::MORETHAN, 4));
    expect($condition->getCondition())->toBe(" WHERE  (id > ?) ");
});


test("test basic condition value", function () {
    $condition = new BasicCondition(new Condition("id", ConditionOperators::MORETHAN, 4));
    expect($condition->getValues())->toBe([4]);
});

test("test basc condition multi values", function () {
    $condition = new BasicCondition(new Condition("id", ConditionOperators::IN, [4,5,6]));
    expect($condition->getValues())->toBe([4,5,6]);
});
