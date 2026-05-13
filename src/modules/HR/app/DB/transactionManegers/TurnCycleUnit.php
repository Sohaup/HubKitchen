<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\TurnCycleMapper;
use PostApi\modules\HR\domain\entities\TurnCycle;

class TurnCycleUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private TurnCycleMapper $turnCycleMapper, private PDO $db) {}
    public function registerNew(TurnCycle &$turnCycle)
    {
        if (!in_array($turnCycle, $this->newObjects, true)) {
            $this->newObjects[$turnCycle->getId()] = $turnCycle;
        }
    }
    public function registerDirty(TurnCycle &$turnCycle)
    {
        if (!in_array($turnCycle, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$turnCycle->getId()] = $turnCycle;
        }
    }
    public function registerDeleted(TurnCycle &$turnCycle)
    {
        if (!in_array($turnCycle, $this->deletedObjects, true)) {
            $this->deletedObjects[$turnCycle->getId()] = $turnCycle;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->turnCycleMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->turnCycleMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->turnCycleMapper->delete($entity->getId());
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
