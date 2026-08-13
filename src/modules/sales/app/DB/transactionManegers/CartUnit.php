<?php

namespace PostApi\modules\sales\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\CartMapper;
use PostApi\modules\sales\domain\entities\Cart;

class CartUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private CartMapper $cartMapper;
    public function __construct(private PDO $db) {
        $this->cartMapper = new CartMapper($db);
    }

    public function registerNew(Cart &$cart)
    {
        if (!in_array($cart, $this->newObjects, true)) {
            $this->newObjects[] = $cart;
        }
    }
    public function registerDirty(Cart &$cart)
    {
        if (!in_array($cart, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $cart;
        }
    }
    public function registerDeleted(Cart &$cart)
    {
        if (!in_array($cart, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $cart;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->cartMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->cartMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->cartMapper->delete($entity->getId());
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
