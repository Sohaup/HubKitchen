<?php

namespace PostApi\modules\inovice\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\inovice\app\DB\models\SupplierMapper;
use PostApi\modules\inovice\domain\entities\Supplier;

class SupplierUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private SupplierMapper $supplierMapper;
    public function __construct(private PDO $db) {
        $this->supplierMapper = new SupplierMapper($db);
    }
    public function registerNew(Supplier &$supplier)
    {
        if (!in_array($supplier, $this->newObjects, true)) {
            $this->newObjects[] = $supplier;
        }
    }
    public function registerDirty(Supplier &$supplier)
    {
        if (!in_array($supplier, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $supplier;
        }
    }
    public function registerDeleted(Supplier &$supplier)
    {
        if (!in_array($supplier, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $supplier;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->supplierMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->supplierMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->supplierMapper->delete($entity->getId());
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
