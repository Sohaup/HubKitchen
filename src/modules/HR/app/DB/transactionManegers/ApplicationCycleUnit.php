<?php
namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\ApplicationCycleMapper;
use PostApi\modules\HR\domain\entities\ApplicationCycle;

class ApplicationCycleUnit {
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];

    public function __construct(private ApplicationCycleMapper $applicationCycleMapper, private PDO $db) {}

    public function registerNew(ApplicationCycle &$applicationCycle)
    {
        if (!in_array($applicationCycle, $this->newObjects, true)) {
            $this->newObjects[$applicationCycle->getId()] = $applicationCycle;
        }
    }
    public function registerDirty(ApplicationCycle &$applicationCycle)
    {
        if (!in_array($applicationCycle, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$applicationCycle->getId()] = $applicationCycle;
        }
    }
    public function registerDeleted(ApplicationCycle &$applicationCycle)
    {
        if (!in_array($applicationCycle, $this->deletedObjects, true)) {
            $this->deletedObjects[$applicationCycle->getId()] = $applicationCycle;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->applicationCycleMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->applicationCycleMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->applicationCycleMapper->delete($entity->getId());
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