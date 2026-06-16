<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\JobMapper;
use PostApi\modules\HR\domain\entities\Job;

class JobUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];

    public function __construct(private PDO $db, private JobMapper $jobMapper) {}

    public function registerNew(Job &$job)
    {
        if (!in_array($job, $this->newObjects, true)) {
            $this->newObjects[$job->getId()] = $job;
        }
    }
    public function registerDirty(Job &$job)
    {
        if (!in_array($job, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$job->getId()] = $job;
        }
    }
    public function registerDeleted(Job &$job)
    {
        if (!in_array($job, $this->deletedObjects, true)) {
            $this->deletedObjects[$job->getId()] = $job;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->jobMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->jobMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->jobMapper->delete($entity->getId());
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
