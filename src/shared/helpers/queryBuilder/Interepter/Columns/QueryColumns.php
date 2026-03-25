<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Columns;


class QueryColumns implements QueryColumnsComponent
{    
    use QueryColumnsImp;
    public function __construct(array $columns)
    {
        $this->setColumns($columns);
    }

}

