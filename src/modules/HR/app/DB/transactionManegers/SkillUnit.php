<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\SkillMapper;
use PostApi\modules\HR\domain\entities\Skill;

class SkillUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private SkillMapper $skillMapper, private PDO $db) {}
    public function registerNew(Skill &$skill)
    {
        if (!in_array($skill, $this->newObjects, true)) {
            $this->newObjects[$skill->getId()] = $skill;
        }
    }
    public function registerDirty(Skill &$skill)
    {
        if (!in_array($skill, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$skill->getId()] = $skill;
        }
    }
    public function registerDeleted(Skill &$skill)
    {
        if (!in_array($skill, $this->deletedObjects, true)) {
            $this->deletedObjects[$skill->getId()] = $skill;
        }
    }
    public function commit() {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->skillMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->skillMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->skillMapper->delete($entity->getId());
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
