<?php

use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumns;
use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumnsWithValues;

test("test get columns method" , function () {
    $columns = new QueryColumns(["name" , "phone"]);
    $columnWithValue = new QueryColumnsWithValues($columns , ["mazen" , "011236458"]);
    expect($columnWithValue->getColumns())->toBe(["name" , "phone"]);
});

test("test get values method" , function () {
    $columns = new QueryColumns(["name" , "phone"]);
    $columnWithValue = new QueryColumnsWithValues($columns , ["mazen" , "011236458"]);
    expect($columnWithValue->getValues())->toBe(["mazen" , "011236458"]);
});