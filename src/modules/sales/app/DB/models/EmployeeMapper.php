<?php

namespace PostApi\modules\sales\app\DB\models;

use Error;
use PDO;
use PDOException;
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
        try {
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
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
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
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = [])
    {
        $query = "SELECT * FROM sales.employees";
        $whereClauses = [];
        $bindings = [];

        if (isset($criteria['user_id'])) {
            $whereClauses[] = "user_id = ?";
            $bindings[] = $criteria['user_id'];
        }

        if (isset($criteria['country'])) {
            $whereClauses[] = "country = ?";
            $bindings[] = $criteria['country'];
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $employeesRawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($employeesRawData as $employeeRawData) {
                $employee = new Employee();
                $employee->setId($employeeRawData['id']);
                $employee->setUserId($employeeRawData['user_id']);
                $employee->setCountry($employeeRawData['country']);
                $this->identityMap[$employeeRawData['id']] = $employee;
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Employee $employee)
    {
        try {
            $createEmployeeQuery = $this->db->prepare("INSERT INTO sales.employees(user_id, country) VALUES(?, ?) RETURNING id");
            $createEmployeeQuery->execute([$employee->getUserId(), $employee->getCountry()]);
            $employeeId = $createEmployeeQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $employee->setId($employeeId);
            $this->identityMap[$employee->getId()] = $employee;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Employee $employee)
    {
        try {
            $updateEmployeeQuery = $this->db->prepare("UPDATE sales.employees SET user_id = ?, country = ? WHERE id = ?");
            $updateEmployeeQuery->execute([$employee->getUserId(), $employee->getCountry(), $employee->getId()]);
            $this->identityMap[$employee->getId()] = $employee;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteEmployeeQuery = $this->db->prepare("DELETE FROM sales.employees WHERE id = ?");
                $deleteEmployeeQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
