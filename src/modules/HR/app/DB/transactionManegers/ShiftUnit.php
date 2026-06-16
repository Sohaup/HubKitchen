<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\ShiftMapper;
use PostApi\modules\HR\domain\entities\Shift;

class ShiftUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private PDO $db, private ShiftMapper $shiftMapper) {}
    public function registerNew(Shift $shift)
    {
        if (!in_array($shift, $this->newObjects, true)) {
            $this->newObjects[] = $shift;
        }
    }
    public function registerDirty(Shift $shift)
    {
        if (!in_array($shift, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $shift;
        }
    }
    public function registerDeleted(Shift $shift)
    {
        if (!in_array($shift, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $shift;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->shiftMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->shiftMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->shiftMapper->delete($entity);
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
