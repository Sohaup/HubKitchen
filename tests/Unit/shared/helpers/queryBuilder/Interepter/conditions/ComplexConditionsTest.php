<?php


use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BasicCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BetweenCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\ComplexCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\ConditionsCollection;

test("complex conditions get condition method " , function () {
    $condition1 = new BasicCondition(new Condition("id" , ConditionOperators::LESSTHANOREQUAL , 6));
    $condition2 = new BetweenCondition(new Condition("name" , ConditionOperators::BETWEEN , ["k" , "y"]));
    $conditionsArr = new ConditionsCollection([$condition1 , $condition2]);
    $conditions = new ComplexCondition($conditionsArr);
    expect($conditions->getCondition())->ToBe(" WHERE    (id <= ?)    (name BETWEEN ? AND ?) ");
});


test("complex conditions get values method " , function () {
    $condition1 = new BasicCondition(new Condition("id" , ConditionOperators::LESSTHANOREQUAL , 6));
    $condition2 = new BetweenCondition(new Condition("name" , ConditionOperators::BETWEEN , ["k" , "y"]));
    $conditionsArr = new ConditionsCollection([$condition1 , $condition2]);
    $conditions = new ComplexCondition($conditionsArr);
    expect($conditions->getValues())->ToBe([6 , "k" , "y"]);
});

test("complex conditions get values method check multable values " , function () {
    $condition1 = new BasicCondition(new Condition("id" , ConditionOperators::IN , [6 , 7]));
    $condition2 = new BetweenCondition(new Condition("name" , ConditionOperators::BETWEEN , ["k" , "y"]));
    $conditionsArr = new ConditionsCollection([$condition1 , $condition2]);
    $conditions = new ComplexCondition($conditionsArr);
    expect($conditions->getValues())->ToBe([6 ,7, "k" , "y"]);
});
