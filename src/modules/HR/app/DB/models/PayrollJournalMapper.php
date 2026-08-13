<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\Addresse;
use PostApi\modules\HR\domain\entities\Department;
use PostApi\modules\HR\domain\entities\Employee;
use PostApi\modules\HR\domain\entities\JobDescription;
use PostApi\modules\HR\domain\entities\PayrollJournal;
use PostApi\modules\HR\domain\entities\SaleryComponent;
use PostApi\modules\HR\domain\entities\Shift;

class PayrollJournalMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $getPayrollQuery = $this->db->prepare("SELECT * FROM HR.employees_view WHERE payroll_id = ?");
        $getPayrollQuery->execute([$id]);
        $payrollRawData = $getPayrollQuery->fetch(PDO::FETCH_ASSOC);
        if ($payrollRawData) {
            $employee = new Employee();
            $job = new JobDescription();
            $shift = new Shift();
            $shift->create(id: $payrollRawData['shift_id'], shiftName: $payrollRawData['shift_name'], startTime: $payrollRawData['shift_start_time'], endTime: $payrollRawData['shift_end_time'], breakDuration: $payrollRawData['shift_break_duration_by_minutes'], isOverNight: $payrollRawData['is_overnight'], isActive: $payrollRawData['is_active'], createdAt: $payrollRawData['shift_created_at']);
            $job->create($payrollRawData['jd_id'], $payrollRawData['jd_ name'], $shift);
            $department = new Department();
            $department->create($payrollRawData['department_id'], $payrollRawData['department_name']);
            $addresse = new Addresse();
            $addresse->create($payrollRawData['addresse_id'], $payrollRawData['country'], $payrollRawData['city'], $payrollRawData['street'], $payrollRawData['flat']);
            $saleryComponent = new SaleryComponent($payrollRawData['selary_component_id'], $payrollRawData['selary_component_name'], $payrollRawData['selary_component_type'], $payrollRawData['selary_component_calc_type']);
            $employee->create($payrollRawData['id'], $payrollRawData['employee_status'], $payrollRawData['martial_status'], $payrollRawData['user_id'], $job, $payrollRawData['manager_id'], $payrollRawData['employeed_at'], $department, $addresse);
            $payroll = new PayrollJournal(id: $payrollRawData['payroll_id'], employee: $employee, saleryComponent: $saleryComponent, amount: $payrollRawData['payroll_amount'], date: $payrollRawData['payroll_date']);
            $this->identityMap[$id] = $payroll;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $getPayrollQuery = $this->db->prepare("SELECT * FROM HR.employees_view WHERE payroll_id IS NOT NULL");
        $getPayrollQuery->execute([]);
        $payrollsRawData = $getPayrollQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($payrollsRawData as $payrollRawData) {
            $employee = new Employee();
            $job = new JobDescription();
            $shift = new Shift();
            $shift->create(id: $payrollRawData['shift_id'], shiftName: $payrollRawData['shift_name'], startTime: $payrollRawData['shift_start_time'], endTime: $payrollRawData['shift_end_time'], breakDuration: $payrollRawData['shift_break_duration_by_minutes'], isOverNight: $payrollRawData['is_overnight'], isActive: $payrollRawData['is_active'], createdAt: $payrollRawData['shift_created_at']);
            $job->create($payrollRawData['jd_id'], $payrollRawData['jd_ name'], $shift);
            $department = new Department();
            $department->create($payrollRawData['department_id'], $payrollRawData['department_name']);
            $addresse = new Addresse();
            $addresse->create($payrollRawData['addresse_id'], $payrollRawData['country'], $payrollRawData['city'], $payrollRawData['street'], $payrollRawData['flat']);
            $saleryComponent = new SaleryComponent($payrollRawData['selary_component_id'], $payrollRawData['selary_component_name'], $payrollRawData['selary_component_type'], $payrollRawData['selary_component_calc_type']);
            $employee->create($payrollRawData['id'], $payrollRawData['employee_status'], $payrollRawData['martial_status'], $payrollRawData['user_id'], $job, $payrollRawData['manager_id'], $payrollRawData['employeed_at'], $department, $addresse);
            $payroll = new PayrollJournal(id: $payrollRawData['payroll_id'], employee: $employee, saleryComponent: $saleryComponent, amount: $payrollRawData['payroll_amount'], date: $payrollRawData['payroll_date']);
            $this->identityMap[$payroll->getId()] = $payroll;
        }
        return $this->identityMap;
    }

    public function create(PayrollJournal $payroll)
    {
        $createPayrollQuery = $this->db->prepare("INSERT INTO HR.payroll_journal(employee_id, selary_component_id, amount) VALUES(? , ? , ? ) RETURNING id");
        $createPayrollQuery->execute([$payroll->getEmployee()->getId(), $payroll->getSaleryComponent()->getId(), $payroll->getAmount()]);
        $PayrollId = $createPayrollQuery->fetch(PDO::FETCH_ASSOC)['id'];
        if ($PayrollId) {
            $payroll->setId($PayrollId);
            $this->identityMap[$PayrollId] = $payroll;
        }
    }

    public function update(PayrollJournal $payroll)
    {
        $updateSaleryQuery = $this->db->prepare("UPDATE HR.payroll_journal SET employee_id = ?, selary_component_id = ?, amount = ? WHERE id = ?");
        $updateSaleryQuery->execute([$payroll->getEmployee()->getId(), $payroll->getSaleryComponent()->getId(), $payroll->getAmount(), $payroll->getId()]);
        $this->identityMap[$payroll->getId()] = $payroll;
    }

    public function delete(int $id)
    {
        $deleteSaleryQuery = $this->db->prepare("DELETE FROM HR.payroll_journal WHERE id = ?");
        $deleteSaleryQuery->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
