<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\Addresse;
use PostApi\modules\HR\domain\entities\Department;
use PostApi\modules\HR\domain\entities\Employee;
use PostApi\modules\HR\domain\entities\JobDescription;
use PostApi\modules\HR\domain\entities\Shift;


class EmployeeMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}
    public function findOne(string $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $getEmployeeQuery = $this->db->prepare("SELECT * FROM HR.employees_view WHERE id = ?");
        $getEmployeeQuery->execute([$id]);
        $employeeRawdata = $getEmployeeQuery->fetch(PDO::FETCH_ASSOC);
        if ($employeeRawdata) {
            $employee = new Employee();
            $job = new JobDescription();
            $shift = new Shift();
            $shift->create(id: $employeeRawdata['shift_id'], shiftName: $employeeRawdata['shift_name'], startTime: $employeeRawdata['shift_start_time'], endTime: $employeeRawdata['shift_end_time'], breakDuration: $employeeRawdata['shift_break_duration_by_minutes'], isOverNight: $employeeRawdata['shift_is_overnight'], isActive: $employeeRawdata['shift_is_active'], createdAt: $employeeRawdata['shift_created_at']);
            $job->create($employeeRawdata['jd_id'], $employeeRawdata['jd_name'], $shift);
            $department = new Department();
            $department->create($employeeRawdata['department_id'], $employeeRawdata['department_name']);
            $addresse = new Addresse();
            $addresse->create($employeeRawdata['addresse_id'], $employeeRawdata['country'], $employeeRawdata['city'], $employeeRawdata['street'], $employeeRawdata['flat']);
            $employee->create($employeeRawdata['id'], $employeeRawdata['employee_status'], $employeeRawdata['martial_status'], $employeeRawdata['user_id'], $job, $employeeRawdata['manager_id'], $employeeRawdata['employeed_at'], $department, $addresse);
            $this->identityMap[$id] = $employee;
            return $employee;
        }
    }
    public function findAll()
    {
        $getEmployeesQuery = $this->db->prepare("SELECT * FROM HR.employees_view");
        $getEmployeesQuery->execute([]);
        $employeeRawdata = $getEmployeesQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($employeeRawdata as $employeeRawdata) {
            if (!isset($this->identityMap[$employeeRawdata['id']])) {
                $employee = new Employee();
                $job = new JobDescription();
                $shift = new Shift();
                $shift->create(id: $employeeRawdata['shift_id'], shiftName: $employeeRawdata['shift_name'], startTime: $employeeRawdata['shift_start_time'], endTime: $employeeRawdata['shift_end_time'], breakDuration: $employeeRawdata['shift_break_duration_by_minutes'], isOverNight: $employeeRawdata['shift_is_overnight'], isActive: $employeeRawdata['shift_is_active'], createdAt: $employeeRawdata['shift_created_at']);
                $job->create($employeeRawdata['jd_id'], $employeeRawdata['jd_name'], $shift);
                $department = new Department();
                $department->create($employeeRawdata['department_id'], $employeeRawdata['department_name']);
                $addresse = new Addresse();
                $addresse->create($employeeRawdata['addresse_id'], $employeeRawdata['country'], $employeeRawdata['city'], $employeeRawdata['street'], $employeeRawdata['flat']);
                $employee->create($employeeRawdata['id'], $employeeRawdata['employee_status'], $employeeRawdata['martial_status'], $employeeRawdata['user_id'], $job, $employeeRawdata['manager_id'], $employeeRawdata['employeed_at'], $department, $addresse);
                $this->identityMap[$employee->getId()] = $employee;
            }
        }
        return $this->identityMap;
    }
    public function create(Employee $employee)
    {
        $createEmployeeQuery = $this->db->prepare("INSERT INTO HR.employees(martial_status , employee_status , user_id , jd_id , manager_id , department_id , addresse_id ) VALUES(? ,?, ? , ? , ? , ? , ?) RETURNING id ");
        $createEmployeeQuery->execute([$employee->getMartialStatus(), $employee->getEmployeeStatus(), $employee->getUserId(), $employee->getJob()->getId(), $employee->getManagerId(), $employee->getDepartment()->getId(), $employee->getAddress()->getId()]);
        $employeeId = $createEmployeeQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $employee->setId($employeeId);
        $this->identityMap[$employeeId] = $employee;
    }
    public function update(Employee $employee)
    {
        if (isset($this->identityMap[$employee->getId()])) {
            $updateEmployeeQuery = $this->db->prepare("UPDATE HR.employees SET  martial_status = ? , employee_status = ? , user_id  = ? , manager_id = ?  , department_id = ? , addresse_id = ? WHERE id = ?");
            $updateEmployeeQuery->execute([$employee->getMartialStatus(), $employee->getEmployeeStatus(), $employee->getUserId(), $employee->getManagerId(), $employee->getDepartment()->getId(), $employee->getAddress()->getId(), $employee->getId()]);
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
