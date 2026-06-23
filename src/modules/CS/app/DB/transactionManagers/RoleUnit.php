<?php

namespace PostApi\modules\CS\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\CS\app\DB\models\RoleMapper;
use PostApi\modules\CS\domain\entities\Role;

class RoleUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private RoleMapper $mapper, private PDO $db) {}
    public function registerNew(Role &$obj)
    {
        if (!in_array($obj, $this->newObjects, true)) $this->newObjects[] = $obj;
    }
    public function registerDirty(Role &$obj)
    {
        if (!in_array($obj, $this->dirtyObjects, true)) $this->dirtyObjects[] = $obj;
    }
    public function registerDeleted(Role &$obj)
    {
        if (!in_array($obj, $this->deletedObjects, true)) $this->deletedObjects[] = $obj;
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) $this->mapper->insert($entity);
            foreach ($this->dirtyObjects as $entity) $this->mapper->update($entity);
            foreach ($this->deletedObjects as $entity) $this->mapper->delete($entity->getId());
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
