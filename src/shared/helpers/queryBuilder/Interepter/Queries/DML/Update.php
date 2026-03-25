<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DML;

use PostApi\shared\helpers\queryBuilder\Interepter\ColumnsIntereptor;
use PostApi\shared\helpers\queryBuilder\Interepter\ConditionIntrepter;
use PostApi\shared\helpers\queryBuilder\Interepter\QueryInterpter;
use PostApi\shared\helpers\queryBuilder\Interepter\TableIntereptor;

class Update extends QueryInterpter implements TableIntereptor, ColumnsIntereptor , ConditionIntrepter
{
    public function __construct(string $table, array $columns, ?string $condition = null)
    {
        $this->currentQuery = "UPDATE ";
        $this->interptTable($table);
        $this->interptColumns($columns);
        !is_null($condition) ? $this->interuptConditions($condition) : "";
        $this->setQuery($this->currentQuery);
    }
    public function interptTable(string $table)
    {
        $this->currentQuery .= " $table ";
    }
    public function interptColumns(array $columns)
    {
        $this->currentQuery .= " SET ";
        foreach ($columns as $index => $column) {
            if ($index == 0) {
                $this->currentQuery .= " $column = ?";
            } else {
                $this->currentQuery .= ", $column = ?";
            }
        }
    }
    public function interuptConditions(string $condition)
    {        
        $this->currentQuery .=  $condition;
    }
}