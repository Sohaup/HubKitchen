<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
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

        $getDepartmentQuery = $this->db->prepare("SELECT * FROM HR.departments WHERE id = ?");
        $getDepartmentQuery->execute([$id]);
        $departmentRawData = $getDepartmentQuery->fetch(PDO::FETCH_ASSOC);
        if ($departmentRawData) {
            $department = new Department();
            $department->setId($departmentRawData['id']);
            $department->setName($departmentRawData['name']);
            $this->identityMap[$departmentRawData['id']];
            return $department;
        }
    }

    public function findAll()
    {
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
    }

    public function create(Department $department)
    {
        $createDepartmentQuery = $this->db->prepare("INSERT INTO HR.departments(name) VALUES(?) RETURNING id ");
        $createDepartmentQuery->execute([$department->getName()]);
        $departmentId = $createDepartmentQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $department->setId($departmentId);
        $this->identityMap[$department->getId()] = $department;
    }

    public function update(Department $department)
    {
        if (isset($this->identityMap[$department->getId()])) {
            $updateDepartmentQuery = $this->db->prepare("UPDATE HR.departments SET name = ? WHERE id = ?");
            $updateDepartmentQuery->execute([$department->getName(), $department->getId()]);
            $this->identityMap[$department->getId()] = $department;            
        }
    }

    public function delete(int $id) {
        if (isset($this->identityMap[$id])) {
            $deleteDepartmentQuery = $this->db->prepare("DELETE FROM HR.departments WHERE id = ?");
            $deleteDepartmentQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
