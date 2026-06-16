<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\PayrollJournal;

class PayrollJournalMapper
{
    private array $identityMap = [];
    private EmployeeMapper $employeeMapper;
    private SaleryComponentMapper $saleryComponentMapper;
    public function __construct(private PDO $db)
    {
        $this->employeeMapper = new EmployeeMapper($this->db);
        $this->saleryComponentMapper = new SaleryComponentMapper($this->db);
    }

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $getPayrollQuery = $this->db->prepare("SELECT * FROM HR.payroll_journal WHERE id = ?");
        $getPayrollQuery->execute([$id]);
        $PayrollRawData = $getPayrollQuery->fetch(PDO::FETCH_ASSOC);
        if ($PayrollRawData) {            
            $employee = $this->employeeMapper->findOne($PayrollRawData['employee_id']);
            $saleryComponent = $this->saleryComponentMapper->findOne($PayrollRawData['selary_component_id']);
            $payroll = new PayrollJournal(id: $PayrollRawData['id'], employee: $employee, saleryComponent: $saleryComponent, amount: $PayrollRawData['amount'], date: $PayrollRawData['date']);
            $this->identityMap[$id] = $payroll;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $getPayrollQuery = $this->db->prepare("SELECT * FROM HR.payroll_journal");
        $getPayrollQuery->execute([]);
        $payrollsRawData = $getPayrollQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($payrollsRawData as $payrollRawData) {
            $employee = $this->employeeMapper->findOne($payrollRawData['employee_id']);
            $saleryComponent = $this->saleryComponentMapper->findOne($payrollRawData['selary_component_id']);
            $payroll = new PayrollJournal(id: $payrollRawData['id'], employee: $employee, saleryComponent: $saleryComponent, amount: $payrollRawData['amount'], date: $payrollRawData['date']);
            if (!isset($this->identityMap[$payroll->getId()])) {
            $this->identityMap[$payroll->getId()] = $payroll;
            }
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
        // if (isset($this->identityMap[$payroll->getId()])) {
            $updateSaleryQuery = $this->db->prepare("UPDATE HR.payroll_journal SET employee_id = ?, selary_component_id = ?, amount = ? WHERE id = ?");
            $updateSaleryQuery->execute([$payroll->getEmployee()->getId(), $payroll->getSaleryComponent()->getId(), $payroll->getAmount(), $payroll->getId()]);
            $this->identityMap[$payroll->getId()] = $payroll;
        // }
    }

    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteSaleryQuery = $this->db->prepare("DELETE FROM HR.payroll_journal WHERE id = ?");
            $deleteSaleryQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
