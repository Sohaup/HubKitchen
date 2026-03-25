<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Columns;

class QueryColumnsWithValues extends QueryColumnsDecorator
{   
    public function __construct(QueryColumnsComponent $columns , array $values)
    {
        parent::__construct($columns);
        $this->setColumns($columns->getColumns());    
        $this->setValues($values);    
    }
    public function flatValues() {
        return array_merge(...$this->getValues());
    }
}