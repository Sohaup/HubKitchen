<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\EvolutionCritiriaMapper;
use PostApi\modules\HR\domain\entities\EvolutionCritiria;

class EvolutionCritiriaUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];

    public function __construct(private EvolutionCritiriaMapper $evolutionCritiriaMapper, private PDO $db) {}

    public function registerNew(EvolutionCritiria &$evolutionCritiria)
    {
        if (!in_array($evolutionCritiria, $this->newObjects, true)) {
            $this->newObjects[$evolutionCritiria->getId()] = $evolutionCritiria;
        }
    }
    public function registerDirty(EvolutionCritiria &$evolutionCritiria)
    {
        if (!in_array($evolutionCritiria, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$evolutionCritiria->getId()] = $evolutionCritiria;
        }
    }
    public function registerDeleted(EvolutionCritiria &$evolutionCritiria)
    {
        if (!in_array($evolutionCritiria, $this->deletedObjects, true)) {
            $this->deletedObjects[$evolutionCritiria->getId()] = $evolutionCritiria;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->evolutionCritiriaMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->evolutionCritiriaMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->evolutionCritiriaMapper->delete($entity->getId());
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
