<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Offset;

class Offset {
    private  string $offset = " OFFSET ";
    public function __construct(int $offset)
    {
        $this->parseOffset($offset);
    }
    public function parseOffset(int $offset) {
        $this->offset .= $offset;
    }
    public function getQuery() {
        return $this->offset;
    }
}