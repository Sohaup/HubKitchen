<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter;

abstract class QueryInterpter
{
    private string $query;
    public string $currentQuery;

    public function setQuery(string $query)
    {
        $this->query = $query;
    }
    public function getQuery()
    {
        return $this->query;
    }
}
