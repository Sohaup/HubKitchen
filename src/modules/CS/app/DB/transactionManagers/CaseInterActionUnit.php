<?php

namespace PostApi\modules\CS\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\CS\app\DB\models\CaseInterActionMapper;
use PostApi\modules\CS\domain\entities\CaseInterAction;

class CaseInterActionUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private CaseInterActionMapper $mapper, private PDO $db) {}
    public function registerNew(CaseInterAction &$entity)
    {
        if (!in_array($entity, $this->newObjects, true)) {
            $this->newObjects[] = $entity;
        }
    }
    public function registerDirty(CaseInterAction &$entity)
    {
        if (!in_array($entity, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $entity;
        }
    }
    public function registerDeleted(CaseInterAction &$entity)
    {
        if (!in_array($entity, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $entity;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->mapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->mapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->mapper->delete($entity->getId());
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
