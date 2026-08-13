<?php

namespace PostApi\modules\sales\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\ProductMapper;
use PostApi\modules\sales\domain\entities\Product;

class ProductUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private ProductMapper $productMapper;
    public function __construct(private PDO $db) {
        $this->productMapper = new ProductMapper($db);
    }

    public function registerNew(Product &$product)
    {
        if (!in_array($product, $this->newObjects, true)) {
            $this->newObjects[] = $product;
        }
    }
    public function registerDirty(Product &$product)
    {
        if (!in_array($product, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $product;
        }
    }
    public function registerDeleted(Product &$product)
    {
        if (!in_array($product, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $product;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->productMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->productMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->productMapper->delete($entity->getId());
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
