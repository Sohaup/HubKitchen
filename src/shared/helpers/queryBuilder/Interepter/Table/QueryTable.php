<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Table;

class QueryTable implements QueryTableInterface
{
    private string $query;
    private string $table;
    public function __construct(string $table)
    {
        $this->table = $table;
        $this->setQuery($table);
    }
    public function setQuery(string $table)
    {
        $this->query = " FROM $table ";
    }
    public function getQuery()
    {       
        return $this->query;
    }
    public function getTable() {
        return $this->table;
    }
}