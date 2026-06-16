<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\AppraiselResultMapper;
use PostApi\modules\HR\domain\entities\AppraiselResult;

class AppraiselResultUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];

    public function __construct(private AppraiselResultMapper $appraiselResultMapper, private PDO $db) {}

    public function registerNew(AppraiselResult &$appraiselResult)
    {
        if (!in_array($appraiselResult, $this->newObjects, true)) {
            $this->newObjects[$appraiselResult->getId()] = $appraiselResult;
        }
    }
    public function registerDirty(AppraiselResult &$appraiselResult)
    {
        if (!in_array($appraiselResult, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$appraiselResult->getId()] = $appraiselResult;
        }
    }
    public function registerDeleted(AppraiselResult &$appraiselResult)
    {
        if (!in_array($appraiselResult, $this->deletedObjects, true)) {
            $this->deletedObjects[$appraiselResult->getId()] = $appraiselResult;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->appraiselResultMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->appraiselResultMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->appraiselResultMapper->delete($entity->getId());
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
