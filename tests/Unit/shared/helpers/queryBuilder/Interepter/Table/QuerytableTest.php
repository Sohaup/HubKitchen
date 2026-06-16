<?php

use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;

test("test query table class" , function () {
    $table = new QueryTable("users");
    expect($table->getTable())->toBe("users");
});

