<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\JobDescriptionMapper;
use PostApi\modules\HR\domain\entities\JobDescription;

class JobDescriptionUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private JobDescriptionMapper $jobDescriptionMapper, private PDO $db) {}
    public function registerNew(JobDescription &$jobDescription)
    {
        if (!in_array($jobDescription, $this->newObjects, true)) {
            $this->newObjects[] = $jobDescription;
        }
    }
    public function registerDirty(JobDescription &$jobDescription)
    {
        if (!in_array($jobDescription, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $jobDescription;
        }
    }
    public function registerDeleted(JobDescription &$jobDescription)
    {
        if (!in_array($jobDescription, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $jobDescription;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->jobDescriptionMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->jobDescriptionMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->jobDescriptionMapper->delete($entity);
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
