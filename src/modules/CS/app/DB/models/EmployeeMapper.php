<?php

namespace PostApi\modules\CS\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\Employee;
use PostApi\modules\CS\domain\entities\Role;

class EmployeeMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        try {
            if (!isset($this->identityMap[$id])) {
                $stmt = $this->db->prepare("SELECT e.* , r.name AS role_name FROM cs.employees AS e LEFT JOIN CS.roles AS r ON e.role_id = r.id  WHERE e.id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) return null;
                $employee = new Employee();
                $employee->setId($row['id']);
                $employee->setUserId($row['user_id']);
                $employee->setEmployeeId($row['employee_id']);
                $role = new Role();
                $role->setId($row['role_id']);
                $role->setName($row['role_name']);
                $employee->setRole($role);
                $this->identityMap[$id] = $employee;
                return $employee;
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT e.* , r.name AS role_name FROM cs.employees AS e LEFT JOIN CS.roles AS r ON e.role_id = r.id");
            $stmt->execute([]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $employee = new Employee();
                    $employee->setId($row['id']);
                    $employee->setUserId($row['user_id']);
                    $employee->setEmployeeId($row['employee_id']);
                    $role = new Role();
                    $role->setId($row['role_id']);
                    $role->setName($row['role_name']);
                    $employee->setRole($role);
                    $this->identityMap[$row['id']] = $employee;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = []): array
    {
        $query = "SELECT e.*, r.name AS role_name FROM cs.employees AS e LEFT JOIN cs.roles AS r ON e.role_id = r.id";
        $whereClauses = [];
        $bindings = [];

        if (!empty($criteria['id'])) {
            $whereClauses[] = "e.id = ?";
            $bindings[] = $criteria['id'];
        }

        if (!empty($criteria['user_id'])) {
            $whereClauses[] = "e.user_id = ?";
            $bindings[] = $criteria['user_id'];
        }

        if (!empty($criteria['employee_id'])) {
            $whereClauses[] = "e.employee_id = ?";
            $bindings[] = $criteria['employee_id'];
        }

        if (!empty($criteria['role_id'])) {
            $whereClauses[] = "e.role_id = ?";
            $bindings[] = $criteria['role_id'];
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
                    $employee = new Employee();
                    $employee->setId($row['id']);
                    $employee->setUserId($row['user_id']);
                    $employee->setEmployeeId($row['employee_id']);
                    $role = new Role();
                    $role->setId($row['role_id']);
                    $role->setName($row['role_name']);
                    $employee->setRole($role);
                    $this->identityMap[$row['id']] = $employee;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function insert(Employee $employee)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.employees(user_id, employee_id, role_id) VALUES(? , ? , ?) RETURNING id");
            $stmt->execute([$employee->getUserId(), $employee->getEmployeeId(), $employee->getRole()?->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $employee->setId($id);
            $this->identityMap[$id] = $employee;
        } catch (PDOException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(Employee $employee)
    {
        try {
            $stmt = $this->db->prepare("UPDATE cs.employees SET user_id = ? , employee_id = ? , role_id = ? WHERE id = ?");
            $stmt->execute([$employee->getUserId(), $employee->getEmployeeId(), $employee->getRole()?->getId(), $employee->getId()]);
            $this->identityMap[$employee->getId()] = $employee;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM cs.employees WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
