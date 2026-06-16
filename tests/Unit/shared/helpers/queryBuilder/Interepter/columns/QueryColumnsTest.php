<?php

use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumns;

test("test get columns method" , function () {
    $columns = new QueryColumns(["name" , "phone"]);
    expect($columns->getColumns())->toBeArray();
});

