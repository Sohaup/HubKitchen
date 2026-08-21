<?php

namespace PostApi\modules\HR\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\HR\domain\entities\Department;

class DepartmentMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getDepartmentQuery = $this->db->prepare("SELECT * FROM HR.departments WHERE id = ?");
            $getDepartmentQuery->execute([$id]);
            $departmentRawData = $getDepartmentQuery->fetch(PDO::FETCH_ASSOC);
            if ($departmentRawData) {
                $department = new Department();
                $department->setId($departmentRawData['id']);
                $department->setName($departmentRawData['name']);
                $this->identityMap[$departmentRawData['id']] = $department;
                return $department;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $getDeartmentsQuery = $this->db->prepare("SELECT * FROM HR.departments ");
            $getDeartmentsQuery->execute([]);
            $departmentsRawData = $getDeartmentsQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($departmentsRawData as $departmentRawData) {
                $department = new Department();
                $department->setId($departmentRawData['id']);
                $department->setName($departmentRawData['name']);
                if (!isset($this->identityMap[$departmentRawData['id']])) {
                    $this->identityMap[$departmentRawData['id']] = $department;
                }
            }

            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = []): array
    {
        $filterDepartmentsQuery = "SELECT * FROM HR.departments";
        $whereClouses = [];
        $bindings = [];

        if (isset($criteria['id'])) {
            $whereClouses[] = "id = ?";
            $bindings[] = $criteria['id'];
        }

        if (isset($criteria['name'])) {
            $whereClouses[] = "name LIKE ?";
            $bindings[] = "%" . $criteria['name'] . "%";
        }

        if (count($whereClouses) > 0) {
            $filterDepartmentsQuery .= " WHERE " . implode(" AND ", $whereClouses);
        }

        try {
            $getDeartmentsQuery = $this->db->prepare($filterDepartmentsQuery);
            $getDeartmentsQuery->execute($bindings);
            $departmentsRawData = $getDeartmentsQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($departmentsRawData as $departmentRawData) {
                if (!isset($this->identityMap[$departmentRawData['id']])) {
                    $department = new Department();
                    $department->setId($departmentRawData['id']);
                    $department->setName($departmentRawData['name']);
                    $this->identityMap[$departmentRawData['id']] = $department;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Department $department)
    {
        try {
            $createDepartmentQuery = $this->db->prepare("INSERT INTO HR.departments(name) VALUES(?) RETURNING id ");
            $createDepartmentQuery->execute([$department->getName()]);
            $departmentId = $createDepartmentQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $department->setId($departmentId);
            $this->identityMap[$department->getId()] = $department;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Department $department)
    {
        try {
            $updateDepartmentQuery = $this->db->prepare("UPDATE HR.departments SET name = ? WHERE id = ?");
            $updateDepartmentQuery->execute([$department->getName(), $department->getId()]);
            $this->identityMap[$department->getId()] = $department;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteDepartmentQuery = $this->db->prepare("DELETE FROM HR.departments WHERE id = ?");
                $deleteDepartmentQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
