<?php

namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Group;

class Group
{
    private string $groupQuery = "";
    public function __construct(private array $columns) {}
    public function count(string $column)
    {
       return $this->handleAddAggregateMethod($column, "count"). ",";
    }
    public function sum(string $column)
    {
      return  $this->handleAddAggregateMethod($column, "sum"). ",";
    }
    public function handleAddAggregateMethod(string $column, string $method)
    {
        $methodInQuery = strtoupper($method);  
        $methodInAlies = ucwords($method);     
        if (in_array($column, $this->columns)) {
            $groupQuery = " $methodInQuery($column) AS $column"."$methodInAlies";            
            $this->columns = array_filter(
                $this->columns,
                function ($value) use ($column) {
                    return $value != $column;
                }
            );
            return $groupQuery;
        }
    }
    public function getColumnsAfterAggregate() {
        return $this->columns;
    }
    public function handleAddGroupByColumns() {
        $groupQuery = " GROUP BY";
        $groupQuery .= " ".implode("," , $this->columns);
        return $groupQuery;
    }
    public function handleHaving(string $column , $operator , $value) {
        $groupQuery = " HAVING $column $operator ? "; 
        return ["query"=>$groupQuery , "value"=>$value];
    }
    public function getQuery() {
        return $this->groupQuery;
    }
}
