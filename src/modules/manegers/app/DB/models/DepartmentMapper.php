<?php

namespace PostApi\modules\manegers\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\manegers\domain\entities\Department;

class DepartmentMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        try {
            if (!isset($this->identityMap[$id])) {
                $stmt = $this->db->prepare("SELECT * FROM manegers.departments WHERE id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) return null;
                $department = new Department();
                $department->setId((int)$row['id']);
                $department->setName($row['name']);
                $this->identityMap[$id] = $department;
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM manegers.departments");
            $stmt->execute([]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $department = new Department();
                    $department->setId((int)$row['id']);
                    $department->setName($row['name']);
                    $this->identityMap[$row['id']] = $department;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = []): array
    {
        $query = "SELECT * FROM manegers.departments";
        $whereClauses = [];
        $bindings = [];

        if (!empty($criteria['id'])) {
            $whereClauses[] = "id = ?";
            $bindings[] = $criteria['id'];
        }

        if (!empty($criteria['name'])) {
            $whereClauses[] = "name LIKE ?";
            $bindings[] = "%" . $criteria['name'] . "%";
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $department = new Department();
                    $department->setId((int)$row['id']);
                    $department->setName($row['name']);
                    $this->identityMap[$row['id']] = $department;
                }
            }

            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function insert(Department $department)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO manegers.departments(name) VALUES(?) RETURNING id");
            $stmt->execute([$department->getName()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $department->setId((int)$id);
            $this->identityMap[$id] = $department;
        } catch (PDOException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(Department $department)
    {
        try {
            $stmt = $this->db->prepare("UPDATE manegers.departments SET name = ? WHERE id = ?");
            $stmt->execute([$department->getName(), $department->getId()]);
            $this->identityMap[$department->getId()] = $department;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM manegers.departments WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
