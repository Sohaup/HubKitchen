<?php

namespace PostApi\modules\manegers\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\manegers\app\DB\models\DepartmentMapper;
use PostApi\modules\manegers\domain\entities\Department;

class DepartmentUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private DepartmentMapper $departmentMapper;
    public function __construct(private PDO $db) {
        $this->departmentMapper= new DepartmentMapper($db);
    }
    public function registerNew(Department &$department)
    {
        if (!in_array($department, $this->newObjects, true)) {
            $this->newObjects[] = $department;
        }
    }
    public function registerDirty(Department &$department)
    {
        if (!in_array($department, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $department;
        }
    }
    public function registerDeleted(Department &$department)
    {
        if (!in_array($department, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $department;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->departmentMapper->insert($entity);
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
