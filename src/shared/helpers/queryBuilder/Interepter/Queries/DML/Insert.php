<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DML;

use PostApi\shared\helpers\queryBuilder\Interepter\ColumnsIntereptor;
use PostApi\shared\helpers\queryBuilder\Interepter\QueryInterpter;
use PostApi\shared\helpers\queryBuilder\Interepter\TableIntereptor;

class Insert extends QueryInterpter implements TableIntereptor, ColumnsIntereptor
{
    public function __construct(string $table, array $columns , array $values)
    {
        $this->currentQuery = "INSERT INTO ";
        $this->interptTable($table);
        $this->interptColumns($columns);
        $this->interuptValues($values);
        $this->setQuery($this->currentQuery);
    }
    public function interptTable(string $table)
    {
        $this->currentQuery .= " $table";
    }
    public function interptColumns(array $columns)
    {
        $this->currentQuery .= "(";
        foreach ($columns as $index => $column) {
            if ($index == 0) {
                $this->currentQuery .= " $column ";
            } else {
                $this->currentQuery .= ", $column ";
            }
        }
        $this->currentQuery .= ")";
    }

    public function interuptValues(array $columnvalues)
    {

        $isMultiRow = false;
        foreach ($columnvalues as $val) {
            if (is_array($val)) {
                $isMultiRow = true;
                $rowCount = count($val);
                break;
            }
        }
        $this->currentQuery .= " VALUES";
        if (!$isMultiRow) {
            $placeholders = array_fill(0, count($columnvalues), '?');
            $this->currentQuery .= "(" . implode(', ', $placeholders) . ")";
        } else {
            $rowCount = count($columnvalues[0]);
            $placeholders = array_fill(0, $rowCount, '?');
            for ($i = 0; $i < count($columnvalues); $i++) {
                if ($i == 0)
                    $this->currentQuery .= "(" .  implode(",", $placeholders) . ") ";
                else
                    $this->currentQuery .= ", (" .  implode(",", $placeholders) . ") ";
            }
        }
    }
}
