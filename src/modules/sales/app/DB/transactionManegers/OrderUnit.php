<?php

namespace PostApi\modules\sales\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\OrderMapper;
use PostApi\modules\sales\domain\entities\Order;

class OrderUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
     private OrderMapper $orderMapper;
    public function __construct(private PDO $db) {
        $this->orderMapper = new OrderMapper($db);
    }

    public function registerNew(Order &$order)
    {
        if (!in_array($order, $this->newObjects, true)) {
            $this->newObjects[] = $order;
        }
    }
    public function registerDirty(Order &$order)
    {
        if (!in_array($order, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $order;
        }
    }
    public function registerDeleted(Order &$order)
    {
        if (!in_array($order, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $order;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->orderMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->orderMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->orderMapper->delete($entity->getId());
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
