<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DML;

use PostApi\shared\helpers\queryBuilder\Interepter\ConditionIntrepter;
use PostApi\shared\helpers\queryBuilder\Interepter\QueryInterpter;
use PostApi\shared\helpers\queryBuilder\Interepter\TableIntereptor;

class Delete extends QueryInterpter implements TableIntereptor , ConditionIntrepter
{
    public function __construct(string $table, ?string $condition = null)
    {
        $this->currentQuery = "DELETE ";
        $this->interptTable($table);
        !is_null($condition) ? $this->interuptConditions($condition) : "";
        $this->setQuery($this->currentQuery);
    }

    public function interptTable(string $table)
    {
        $this->currentQuery .= " FROM $table ";
    } 
       
    public function interuptConditions(string $condition)
    {       
        $this->currentQuery .=  $condition;
    }
}