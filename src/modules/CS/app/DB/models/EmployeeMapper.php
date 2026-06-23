<?php

namespace PostApi\modules\CS\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\auth\app\DB\models\UserMapper;
use PostApi\modules\CS\domain\entities\Employee;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\HR\app\DB\models\EmployeeMapper as ModelsEmployeeMapper;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\domain\entities\Employee as HrEmployee;

class EmployeeMapper
{
    private array $identityMap = [];
    private ModelsEmployeeMapper $employeeMapper;
    private UserMapper $userMapper;
    private RoleMapper $roleMapper;
    public function __construct(private PDO $db)
    {
        $this->employeeMapper = new ModelsEmployeeMapper($this->db);
        $this->userMapper = new UserMapper($this->db);
        $this->roleMapper = new RoleMapper($this->db);
    }

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM cs.employees WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $employee = new Employee();
            $employee->setId($row['id']);            
            $user = $this->userMapper->findOne($row['user_id']);
            $employee->setUser($user);
            $hremployee = $this->employeeMapper->findOne($row['employee_id']);
            $hremployee->setId($row['employee_id']);
            $employee->setEmployee($hremployee);
            $role = $this->roleMapper->findOne($row['role_id']);
            $employee->setRole($role);
            return $employee;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM cs.employees");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $employee = new Employee();
                $employee->setId($row['id']);
                $userRepo = new UserRepository();
                $user = $userRepo->findOne($row['user_id']);
                $employee->setUser($user);
                $hremployee = $this->employeeMapper->findOne($row['employee_id']);
                $hremployee->setId($row['employee_id']);
                $employee->setEmployee($hremployee);
                $role = $this->roleMapper->findOne($row['role_id']);
                $employee->setRole($role);
                $this->identityMap[$row['id']] = $employee;
            }
        }
        return $this->identityMap;
    }

    public function insert(Employee $employee)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.employees(user_id, employee_id, role_id) VALUES(? , ? , ?) RETURNING id");
            $stmt->execute([$employee->getUser()->getId(), $employee->getEmployee()->getId(), $employee->getRole()?->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $employee->setId($id);
            $this->identityMap[$id] = $employee;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Employee $employee)
    {
        $stmt = $this->db->prepare("UPDATE cs.employees SET user_id = ? , employee_id = ? , role_id = ? WHERE id = ?");
        $stmt->execute([$employee->getUser()->getId(), $employee->getEmployee()->getId(), $employee->getRole()?->getId(), $employee->getId()]);
        $this->identityMap[$employee->getId()] = $employee;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM cs.employees WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
