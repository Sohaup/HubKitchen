<?php

namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL;

use PostApi\shared\helpers\queryBuilder\Interepter\ColumnsIntereptor;
use PostApi\shared\helpers\queryBuilder\Interepter\ConditionIntrepter;
use PostApi\shared\helpers\queryBuilder\Interepter\QueryInterpter;
use PostApi\shared\helpers\queryBuilder\Interepter\TableIntereptor;

class Select extends QueryInterpter implements TableIntereptor, ColumnsIntereptor, ConditionIntrepter
{
    public function __construct(string $table, array $columns, ?string $condition = null, ?string $aggregate = null, ?string $groupBy = null, ?string $having = null, ?string $orderBy = null, ?string $limit = null, ?string $offset = null)
    {
        $this->currentQuery = "SELECT ";
        !is_null($aggregate) ? $this->interuptAggregate($aggregate) : " ";
        $this->interptColumns($columns);
        $this->interptTable($table);
        !is_null($condition) ? $this->interuptConditions($condition) : "";
        !is_null($groupBy) ? $this->interuptGroupBy($groupBy) : " ";
        !is_null($having) ? $this->interuptHaving($having) : " ";
        !is_null($orderBy) ? $this->interuptOrder($orderBy) : "";
        !is_null($limit) ? $this->interuptLimit($limit) : "";
        !is_null($offset) ? $this->interuptOffset($offset) : "";
        $this->setQuery($this->currentQuery);
    }

    public function interptTable(string $table)
    {
        $this->currentQuery .= " $table ";
    }
    public function interptColumns(array $columns)
    {
        $this->currentQuery .= implode(",", $columns);
    }
    public function interuptConditions(string $condition)
    {
        $this->currentQuery .=  $condition;
    }
    public function interuptOrder(string $orderBy)
    {
        $this->currentQuery .= $orderBy;
    }
    public function interuptLimit(string $limitQuery)
    {
        $this->currentQuery .= $limitQuery;
    }
    public function interuptOffset(string $offsetQuery)
    {
        $this->currentQuery .= $offsetQuery;
    }
    public function interuptAggregate(string $aggregate)
    {
        $this->currentQuery .= $aggregate;
    }
    public function interuptGroupBy(string $groupBy)
    {
        $this->currentQuery .= $groupBy;
    }
    public function interuptHaving(string $having)
    {
        $this->currentQuery .= $having;
    }
}
