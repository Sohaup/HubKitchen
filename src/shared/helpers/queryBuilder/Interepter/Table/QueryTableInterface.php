<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Table;

interface QueryTableInterface {
    public function setQuery(string $table);
    public function getQuery();
}