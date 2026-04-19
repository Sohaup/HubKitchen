<?php

namespace PostApi\modules\auth\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\auth\app\DB\models\RoleMapper;
use PostApi\modules\auth\domain\Entities\Role;

class RoleUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private RoleMapper $roleMapper, private PDO $db) {}
    public function registerNew(Role &$role)
    {
        if (!isset($this->newObjects[$role->getId()])) {
            $this->newObjects[$role->getId()] = $role;
        }
    }
    public function registerDirty(Role &$role)
    {
        if (!isset($this->dirtyObjects[$role->getId()])) {
            $this->dirtyObjects[$role->getId()] = $role;
        }
    }
    public function registerDeleted(Role &$role)
    {
        if (!isset($this->deletedObjects[$role->getId()])) {
            $this->deletedObjects[$role->getId()] = $role;
        }
    }

    public function commit() {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->roleMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->roleMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->roleMapper->delete($entity->getId());
            }
            $this->db->commit();
            $this->newObjects = [];
            $this->dirtyObjects = [];
            $this->deletedObjects = [];
        } catch(PDOException $error) {
            $this->db->rollBack();
            throw $error;
        }
    }
}
