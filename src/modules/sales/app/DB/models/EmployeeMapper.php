<?php

namespace PostApi\modules\sales\app\DB\models;

use PDO;
use PostApi\modules\sales\domain\entities\Employee;

class EmployeeMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getEmployeeQuery = $this->db->prepare("SELECT * FROM sales.employees WHERE id = ?");
        $getEmployeeQuery->execute([$id]);
        $employeeRawData = $getEmployeeQuery->fetch(PDO::FETCH_ASSOC);
        if ($employeeRawData) {
            $employee = new Employee();
            $employee->setId($employeeRawData['id']);
            $employee->setUserId($employeeRawData['user_id']);
            $employee->setCountry($employeeRawData['country']);
            $this->identityMap[$employeeRawData['id']] = $employee;
            return $employee;
        }
    }

    public function findAll()
    {
        $getEmployeesQuery = $this->db->prepare("SELECT * FROM sales.employees ");
        $getEmployeesQuery->execute([]);
        $employeesRawData = $getEmployeesQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($employeesRawData as $employeeRawData) {
            if (!isset($this->identityMap[$employeeRawData['id']])) {
                $employee = new Employee();
                $employee->setId($employeeRawData['id']);
                $employee->setUserId($employeeRawData['user_id']);
                $employee->setCountry($employeeRawData['country']);
                $this->identityMap[$employeeRawData['id']] = $employee;
            }
        }
        return $this->identityMap;
    }

    public function create(Employee $employee)
    {
        $createEmployeeQuery = $this->db->prepare("INSERT INTO sales.employees(user_id, country) VALUES(?, ?) RETURNING id");
        $createEmployeeQuery->execute([$employee->getUserId(), $employee->getCountry()]);
        $employeeId = $createEmployeeQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $employee->setId($employeeId);
        $this->identityMap[$employee->getId()] = $employee;
    }

    public function update(Employee $employee)
    {
        $updateEmployeeQuery = $this->db->prepare("UPDATE sales.employees SET user_id = ?, country = ? WHERE id = ?");
        $updateEmployeeQuery->execute([$employee->getUserId(), $employee->getCountry(), $employee->getId()]);
        $this->identityMap[$employee->getId()] = $employee;
    }

    public function delete(string $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteEmployeeQuery = $this->db->prepare("DELETE FROM sales.employees WHERE id = ?");
            $deleteEmployeeQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
