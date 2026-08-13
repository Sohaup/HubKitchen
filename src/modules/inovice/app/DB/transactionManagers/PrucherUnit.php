<?php

namespace PostApi\modules\inovice\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\inovice\app\DB\models\PrucherMapper;
use PostApi\modules\inovice\domain\entities\Prucher;

class PrucherUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private PrucherMapper $prucherMapper;
    public function __construct(private PDO $db) {
        $this->prucherMapper = new PrucherMapper($db);
    }
    public function registerNew(Prucher &$prucher)
    {
        if (!in_array($prucher, $this->newObjects, true)) {
            $this->newObjects[] = $prucher;
        }
    }
    public function registerDirty(Prucher &$prucher)
    {
        if (!in_array($prucher, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $prucher;
        }
    }
    public function registerDeleted(Prucher &$prucher)
    {
        if (!in_array($prucher, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $prucher;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->prucherMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->prucherMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->prucherMapper->delete($entity->getId());
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
