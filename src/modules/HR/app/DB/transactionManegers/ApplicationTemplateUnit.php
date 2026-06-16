<?php
namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\ApplicationTemplateMapper;
use PostApi\modules\HR\domain\entities\ApplicationTemplate;

class ApplicationTemplateUnit {
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];

    public function __construct(private ApplicationTemplateMapper $applicationTemplateMapper, private PDO $db) {}

    public function registerNew(ApplicationTemplate &$applicationTemplate)
    {
        if (!in_array($applicationTemplate, $this->newObjects, true)) {
            $this->newObjects[$applicationTemplate->getId()] = $applicationTemplate;
        }
    }
    public function registerDirty(ApplicationTemplate &$applicationTemplate)
    {
        if (!in_array($applicationTemplate, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$applicationTemplate->getId()] = $applicationTemplate;
        }
    }
    public function registerDeleted(ApplicationTemplate &$applicationTemplate)
    {
        if (!in_array($applicationTemplate, $this->deletedObjects, true)) {
            $this->deletedObjects[$applicationTemplate->getId()] = $applicationTemplate;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->applicationTemplateMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->applicationTemplateMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->applicationTemplateMapper->delete($entity->getId());
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