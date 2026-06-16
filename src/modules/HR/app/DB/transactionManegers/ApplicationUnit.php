<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\ApplicationMapper;
use PostApi\modules\HR\domain\entities\Application;

class ApplicationUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];

    public function __construct(private ApplicationMapper $applicationMapper, private PDO $db) {}

    public function registerNew(Application &$application)
    {
        if (!in_array($application, $this->newObjects, true)) {
            $this->newObjects[$application->getId()] = $application;
        }
    }
    public function registerDirty(Application &$application)
    {
        if (!in_array($application, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$application->getId()] = $application;
        }
    }
    public function registerDeleted(Application &$application)
    {
        if (!in_array($application, $this->deletedObjects, true)) {
            $this->deletedObjects[$application->getId()] = $application;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->applicationMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->applicationMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->applicationMapper->delete($entity->getId());
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
