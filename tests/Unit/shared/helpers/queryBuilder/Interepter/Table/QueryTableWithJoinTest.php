<?php

use PostApi\shared\helpers\queryBuilder\Interepter\Table\JoinCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\JoinTypes;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTableWithJoin;

test("test add table" , function () {
    $queyTable = new QueryTable("users");
    $queryTableWithJoin = new QueryTableWithJoin($queyTable);    
    expect($queryTableWithJoin->getQuery())->toBe(" FROM users  ");
});

test("test add join method" , function () {
    $queyTable = new QueryTable("users");
    $queryTableWithJoin = new QueryTableWithJoin($queyTable);
    $joinCondition = new JoinCondition("users" , "posts" , "id" , "user_id");
    $queryTableWithJoin->addJoin(JoinTypes::RIGHT , "posts" , $joinCondition->parseJoinCondition());
    expect($queryTableWithJoin->getQuery())->toBe(' FROM users   RIGHT JOIN posts  ON users.id = posts.user_id ');
});
