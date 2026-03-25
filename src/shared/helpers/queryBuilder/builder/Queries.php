<?php
namespace PostApi\shared\helpers\queryBuilder\builder;

interface Queries
{
    public function select(string $query, array $values, int $fetchOption);
    public function insert(string $query, array $values);
    public function update(string $query, array $values);
    public function delete(string $query, array $values);
}