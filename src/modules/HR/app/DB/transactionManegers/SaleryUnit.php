<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\SaleryMapper;
use PostApi\modules\HR\domain\entities\Salery;

class SaleryUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private PDO $db, private SaleryMapper $saleryMapper) {}
    public function registerNew(Salery $salery)
    {
        if (!in_array($salery, $this->newObjects, true)) {
            $this->newObjects[] = $salery;
        }
    }
    public function registerDirty(Salery $salery)
    {
        if (!in_array($salery, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $salery;
        }
    }
    public function registerDeleted(Salery $salery)
    {
        if (!in_array($salery, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $salery;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->saleryMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->saleryMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->saleryMapper->delete($entity);
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
