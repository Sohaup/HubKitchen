<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\PayrollJournalMapper;
use PostApi\modules\HR\domain\entities\PayrollJournal;

class PayrollUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private PDO $db, private PayrollJournalMapper $payrollMapper) {}
    public function registerNew(PayrollJournal $payroll)
    {
        if (!in_array($payroll, $this->newObjects, true)) {
            $this->newObjects[] = $payroll;
        }
    }
    public function registerDirty(PayrollJournal $payroll)
    {
        if (!in_array($payroll, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $payroll;
        }
    }
    public function registerDeleted(PayrollJournal $payroll)
    {
        if (!in_array($payroll, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $payroll;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->payrollMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->payrollMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->payrollMapper->delete($entity);
            }
            $this->db->commit();
            $this->newObjects = [];
            $this->dirtyObjects = [];
            $this->deletedObjects = [];
        } catch (PDOException $error) {
            $this->db->rollBack();
            echo $error->getMessage();
        }
    }
}
