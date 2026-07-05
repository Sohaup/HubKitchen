<?php

namespace PostApi\modules\manegers\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\manegers\app\DB\models\ManegerMapper;
use PostApi\modules\manegers\domain\entities\Maneger;

class ManegerUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private ManegerMapper $manegerMapper, private PDO $db) {}
    public function registerNew(Maneger &$maneger)
    {
        if (!in_array($maneger, $this->newObjects, true)) {
            $this->newObjects[] = $maneger;
        }
    }
    public function registerDirty(Maneger &$maneger)
    {
        if (!in_array($maneger, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $maneger;
        }
    }
    public function registerDeleted(Maneger &$maneger)
    {
        if (!in_array($maneger, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $maneger;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->manegerMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->manegerMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->manegerMapper->delete($entity->getId());
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
