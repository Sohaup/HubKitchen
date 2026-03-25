<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Columns;


abstract class QueryColumnsDecorator implements QueryColumnsComponent
{
    protected QueryColumnsComponent $queryColumns;
    protected array $values;
    use QueryColumnsImp;
    public function __construct(QueryColumnsComponent $columns) {
        $this->queryColumns = $columns;
    }
    public function setValues(array $values)
    {
        $this->values = $values;
    }
    public function getValues() {
        return $this->values; 
    }
}