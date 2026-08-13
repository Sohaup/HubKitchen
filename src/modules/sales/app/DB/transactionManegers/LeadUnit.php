<?php

namespace PostApi\modules\sales\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\LeadMapper;
use PostApi\modules\sales\domain\entities\Lead;

class LeadUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private LeadMapper $leadMapper;
    public function __construct(private PDO $db) {
        $this->leadMapper = new LeadMapper($db);
    }

    public function registerNew(Lead &$lead)
    {
        if (!in_array($lead, $this->newObjects, true)) {
            $this->newObjects[] = $lead;
        }
    }
    public function registerDirty(Lead &$lead)
    {
        if (!in_array($lead, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $lead;
        }
    }
    public function registerDeleted(Lead &$lead)
    {
        if (!in_array($lead, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $lead;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->leadMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->leadMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->leadMapper->delete($entity->getId());
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
