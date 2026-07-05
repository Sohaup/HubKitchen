<?php

namespace PostApi\modules\manegers\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\manegers\app\DB\models\TaskMapper;
use PostApi\modules\manegers\domain\entities\Task;

class TaskUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private TaskMapper $taskMapper, private PDO $db) {}
    public function registerNew(Task &$task)
    {
        if (!in_array($task, $this->newObjects, true)) {
            $this->newObjects[] = $task;
        }
    }
    public function registerDirty(Task &$task)
    {
        if (!in_array($task, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $task;
        }
    }
    public function registerDeleted(Task &$task)
    {
        if (!in_array($task, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $task;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->taskMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->taskMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->taskMapper->delete($entity->getId());
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
