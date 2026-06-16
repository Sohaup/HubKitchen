<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\auth\app\DB\models\UserMapper;
use PostApi\modules\HR\domain\entities\Employee;

class EmployeeMapper
{
    private array $identityMap = [];
    private AddresseMapper $addresseMapper;
    private DepartmentMapper $departmentMapper;
    private UserMapper $userMapper;
    private JobDescriptionMapper $jobDescriptionMapper;
    public function __construct(private PDO $db)
    {
        $this->addresseMapper = new AddresseMapper($db);
        $this->departmentMapper = new DepartmentMapper($db);
        $this->userMapper = new UserMapper($db);
        $this->jobDescriptionMapper = new JobDescriptionMapper($db);
    }
    public function findOne(string $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $getEmployeeQuery = $this->db->prepare("SELECT * FROM HR.employees WHERE id = ?");
        $getEmployeeQuery->execute([$id]);
        $employeeRawdata = $getEmployeeQuery->fetch(PDO::FETCH_ASSOC);
        if ($employeeRawdata) {
            $employee = new Employee();
            $employee->setId($employeeRawdata['id']);
            $addresse = $this->addresseMapper->findOne($employeeRawdata['addresse_id']);
            $employee->setAddress($addresse);
            $department = $this->departmentMapper->findOne($employeeRawdata['department_id']);
            $employee->setDepartment($department);
            $job = $this->jobDescriptionMapper->findOne($employeeRawdata['jd_id']);
            $employee->setJob($job);
            $manager = $this->userMapper->findOne($employeeRawdata['manager_id']);
            $employee->setManager($manager);
            $user = $this->userMapper->findOne($employeeRawdata['user_id']);
            $employee->setUser($user);
            $employee->setEmployeedAt($employeeRawdata['employeed_at']);
            $employee->setEmployeeStatus($employeeRawdata['employee_status']);
            $employee->setMartialStatus($employeeRawdata['martial_status']);
            $this->identityMap[$id] = $employee;
            return $employee;
        }
        
    }
    public function findAll()
    {
        $getEmployeesQuery = $this->db->prepare("SELECT * FROM HR.employees ");
        $getEmployeesQuery->execute([]);
        $employeesRawdata = $getEmployeesQuery->fetchAll(PDO::FETCH_ASSOC);       
        foreach ($employeesRawdata as $employeeRawdata) {
            if (!isset($this->identityMap[$employeeRawdata['id']])) {
                $employee = new Employee();
                $employee->setId($employeeRawdata['id']);
                $addresse = $this->addresseMapper->findOne($employeeRawdata['addresse_id']);
                $employee->setAddress($addresse);
                $department = $this->departmentMapper->findOne($employeeRawdata['department_id']);
                $employee->setDepartment($department);
                $job = $this->jobDescriptionMapper->findOne($employeeRawdata['jd_id']);
                $employee->setJob($job);
                $manager = $this->userMapper->findOne($employeeRawdata['manager_id']);
                $employee->setManager($manager);
                $user = $this->userMapper->findOne($employeeRawdata['user_id']);
                $employee->setUser($user);
                $employee->setEmployeedAt($employeeRawdata['employeed_at']);
                $employee->setEmployeeStatus($employeeRawdata['employee_status']);
                $employee->setMartialStatus($employeeRawdata['martial_status']);
                $this->identityMap[$employee->getId()] = $employee;
            }
            
        }        
        return $this->identityMap;
    }
    public function create(Employee $employee)
    {
        $createEmployeeQuery = $this->db->prepare("INSERT INTO HR.employees(martial_status , employee_status , user_id , jd_id , manager_id , department_id , addresse_id ) VALUES(? ,?, ? , ? , ? , ? , ?) RETURNING id ");
        $createEmployeeQuery->execute([$employee->getMartialStatus(), $employee->getEmployeeStatus(), $employee->getUser()->getId(), $employee->getJob()->getId(), $employee->getManager()->getId(), $employee->getDepartment()->getId(), $employee->getAddress()->getId()]);
        $employeeId = $createEmployeeQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $employee->setId($employeeId);
        $this->identityMap[$employeeId] = $employee;
    }
    public function update(Employee $employee)
    {
        if (isset($this->identityMap[$employee->getId()])) {
            $updateEmployeeQuery = $this->db->prepare("UPDATE HR.employees SET  martial_status = ? , employee_status = ? , user_id  = ? , manager_id = ?  , department_id = ? , addresse_id = ? WHERE id = ?");
            $updateEmployeeQuery->execute([$employee->getMartialStatus(), $employee->getEmployeeStatus(), $employee->getUser()->getId(), $employee->getManager()->getId(), $employee->getDepartment()->getId(), $employee->getAddress()->getId(), $employee->getId()]);
            $this->identityMap[$employee->getId()] = $employee;
        }
    }
    public function delete(string $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteEmployeeQuery = $this->db->prepare("DELETE FROM HR.employees WHERE id = ?");
            $deleteEmployeeQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }        
    }
}
