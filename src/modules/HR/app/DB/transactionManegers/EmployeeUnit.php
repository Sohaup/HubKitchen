<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\EmployeeMapper;
use PostApi\modules\HR\domain\entities\Employee;

class EmployeeUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private PDO $db, private EmployeeMapper $employeeMapper) {}

    public function registerNew(Employee &$employee)
    {
        if (!in_array($employee, $this->newObjects, true)) {
            $this->newObjects[] = $employee;
        }
    }
    public function registerDirty(Employee &$employee)
    {
        if (!in_array($employee, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $employee;
        }
    }
    public function registerDeleted(Employee &$employee)
    {
        if (!in_array($employee, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $employee;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->employeeMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->employeeMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->employeeMapper->delete($entity->getId());
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
