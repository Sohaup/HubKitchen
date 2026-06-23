<?php

namespace PostApi\modules\CS\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\CS\app\DB\models\ActionMapper;
use PostApi\modules\CS\domain\entities\Action;

class ActionUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private ActionMapper $actionMapper, private PDO $db) {}
    public function registerNew(Action &$action)
    {
        if (!in_array($action, $this->newObjects, true)) {
            $this->newObjects[] = $action;
        }
    }
    public function registerDirty(Action &$action)
    {
        if (!in_array($action, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $action;
        }
    }
    public function registerDeleted(Action &$action)
    {
        if (!in_array($action, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $action;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->actionMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->actionMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->actionMapper->delete($entity->getId());
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
