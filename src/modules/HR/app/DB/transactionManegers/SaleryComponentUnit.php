<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\SaleryComponentMapper;
use PostApi\modules\HR\domain\entities\SaleryComponent;

class SaleryComponentUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private PDO $db, private SaleryComponentMapper $saleryComponentMapper) {}
    public function registerNew(SaleryComponent $saleryComponent)
    {
        if (!in_array($saleryComponent, $this->newObjects, true)) {
            $this->newObjects[] = $saleryComponent;
        }
    }
    public function registerDirty(SaleryComponent $saleryComponent)
    {
        if (!in_array($saleryComponent, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $saleryComponent;
        }
    }
    public function registerDeleted(SaleryComponent $saleryComponent)
    {
        if (!in_array($saleryComponent, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $saleryComponent;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->saleryComponentMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->saleryComponentMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->saleryComponentMapper->delete($entity);
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
