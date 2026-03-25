<?php

use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTableDecorator;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTableInterface;


test("is tables decorator class abstract" , function () {
    $table = new QueryTable("users");
   
    expect(QueryTableDecorator::class)->toBeAbstract();
});

// test("test the query method" , function () {
//     $table = new QueryTable("users");
//     $decoratorTable = new QueryTableDecorator($table);
//     $decoratorTable->setQuery("posts");
//     $decoratorTable->setQuery(" INNER JOIN users");
//     expect(trim($table->getQuery()))->toBe("FROM posts");
// });