<?php

namespace PostApi\modules\auth\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\auth\app\DB\models\PermissionMapper;
use PostApi\modules\auth\domain\Entities\Permission;

class PermissionUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];

    public function __construct(private PermissionMapper $permissionMapper, private PDO $db) {}
    public function registerNew(Permission &$permission)
    {
        if (!isset($this->newObjects[$permission->getId()])) {
            $this->newObjects[$permission->getId()] = $permission;
        }
    }
    public function registerDirty(Permission &$permission)
    {
        if (!isset($this->dirtyObjects[$permission->getId()])) {
            $this->dirtyObjects[$permission->getId()] = $permission;
        }
    }
    public function registerDeleted(Permission &$permission)
    {
        if (!isset($this->deletedObjects[$permission->getId()])) {
            $this->deletedObjects[$permission->getId()] = $permission;
        }
    }
    public function commit() {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity ) {
                $this->permissionMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->permissionMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->permissionMapper->delete($entity->getId());
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
