<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\DepartmentMapper;
use PostApi\modules\HR\domain\entities\Department;

class DepartmentUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];

    public function __construct(private DepartmentMapper $departmentMapper, private PDO $db) {}

    public function registerNew(Department &$department)
    {
        if (!in_array($department, $this->newObjects, true)) {
            $this->newObjects[$department->getId()] = $department;
        }
    }
    public function registerDirty(Department &$department)
    {
        if (!in_array($department, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$department->getId()] = $department;
        }
    }
    public function registerDeleted(Department &$department)
    {
        if (!in_array($department, $this->deletedObjects, true)) {
            $this->deletedObjects[$department->getId()] = $department;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->departmentMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->departmentMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->departmentMapper->delete($entity->getId());
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
