<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Columns;

interface QueryColumnsComponent
{
    public function setColumns(array $columns);
    public function getColumns();
}

