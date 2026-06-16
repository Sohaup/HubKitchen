<?php

namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\PayrollJournalMapper;
use PostApi\modules\HR\domain\entities\PayrollJournal;
use PostApi\shared\templates\DB_Trait;

class PayrollRepository
{
    private PayrollJournalMapper $payrollMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->payrollMapper = new PayrollJournalMapper($this->postgre->pdo);
    }
    public function findOne(int $id)
    {
        return $this->payrollMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->payrollMapper->findAll();
    }

    public function create(PayrollJournal $payroll)
    {
        $this->payrollMapper->create($payroll);
    }

    public function update(PayrollJournal $payroll)
    {
        $this->payrollMapper->update($payroll);
    }

    public function delete(int $id)
    {
        $this->payrollMapper->delete($id);
    }
}
