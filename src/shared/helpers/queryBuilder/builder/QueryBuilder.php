<?php
namespace PostApi\shared\helpers\queryBuilder\builder;
use PDO; 


class QueryBuilder implements Queries
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function select(string $query, array $values, int $fetchOption)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt->fetchAll($fetchOption);
    }
    public function insert(string $query, array $values)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $this;
    }    
    public function update(string $query, array $values)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $this;
    }
    public function delete(string $query, array $values)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $this;
    }
}
