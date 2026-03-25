<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Limit;

class Limit
{
    private  string $query = " LIMIT ";
    public function __construct(int $limit)
    {
        $this->parseLimit($limit);
    }
    public  function parseLimit(int $limit)
    {
        $this->query .= $limit;
    }
    public  function getQuery()
    {
        return $this->query;
    }
}
