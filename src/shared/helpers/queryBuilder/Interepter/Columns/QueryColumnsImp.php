<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Columns;

trait QueryColumnsImp
{
    protected array $columns = [];
    public function setColumns(array $columns) {
        $this->columns = $columns;
    }
    public function getColumns() {
        return $this->columns;
    }
}