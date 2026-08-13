<?php

namespace PostApi\modules\manegers\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\manegers\app\DB\models\PlanMapper;
use PostApi\modules\manegers\domain\entities\Plan;

class PlanUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private PlanMapper $planMapper;
    public function __construct(private PDO $db) {
        $this->planMapper = new PlanMapper($db);
    }
    public function registerNew(Plan &$plan)
    {
        if (!in_array($plan, $this->newObjects, true)) {
            $this->newObjects[] = $plan;
        }
    }
    public function registerDirty(Plan &$plan)
    {
        if (!in_array($plan, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $plan;
        }
    }
    public function registerDeleted(Plan &$plan)
    {
        if (!in_array($plan, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $plan;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->planMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->planMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->planMapper->delete($entity->getId());
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
