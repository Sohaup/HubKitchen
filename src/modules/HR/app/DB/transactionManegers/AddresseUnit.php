<?php

namespace PostApi\modules\HR\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\HR\app\DB\models\AddresseMapper;
use PostApi\modules\HR\domain\entities\Addresse;

class AddresseUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];

    public function __construct(private AddresseMapper $addresseMapper, private PDO $db) {}

    public function registerNew(Addresse &$addresse)
    {
        if (!in_array($addresse, $this->newObjects, true)) {
            $this->newObjects[$addresse->getId()] = $addresse;
        }
    }
    public function registerDirty(Addresse &$addresse)
    {
        if (!in_array($addresse, $this->dirtyObjects, true)) {
            $this->dirtyObjects[$addresse->getId()] = $addresse;
        }
    }
    public function registerDeleted(Addresse &$addresse)
    {
        if (!in_array($addresse, $this->deletedObjects, true)) {
            $this->deletedObjects[$addresse->getId()] = $addresse;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->addresseMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->addresseMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->addresseMapper->delete($entity->getId());
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
