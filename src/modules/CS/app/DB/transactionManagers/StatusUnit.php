<?php

namespace PostApi\modules\CS\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\CS\app\DB\models\StatusMapper;
use PostApi\modules\CS\domain\entities\Status;

class StatusUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private StatusMapper $statusMapper, private PDO $db) {}
    public function registerNew(Status &$status)
    {
        if (!in_array($status, $this->newObjects, true)) {
            $this->newObjects[] = $status;
        }
    }
    public function registerDirty(Status &$status)
    {
        if (!in_array($status, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $status;
        }
    }
    public function registerDeleted(Status &$status)
    {
        if (!in_array($status, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $status;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->statusMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->statusMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->statusMapper->delete($entity->getId());
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
