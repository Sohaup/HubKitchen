<?php

use PostApi\shared\helpers\queryBuilder\Interepter\Table\JoinCondition;

test("test join condition query" , function () {
    $joinCondition = new JoinCondition("users" ,"posts", "id" , "user_id" );
    expect($joinCondition->parseJoinCondition())->toBe(" ON users.id = posts.user_id ");
});