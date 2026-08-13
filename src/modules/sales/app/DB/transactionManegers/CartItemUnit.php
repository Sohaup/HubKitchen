<?php

namespace PostApi\modules\sales\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\CartItemMapper;
use PostApi\modules\sales\domain\entities\CartItem;

class CartItemUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private CartItemMapper $cartItemMapper;
    public function __construct(private PDO $db) {
        $this->cartItemMapper = new CartItemMapper($db);
    }

    public function registerNew(CartItem &$cartItem)
    {
        if (!in_array($cartItem, $this->newObjects, true)) {
            $this->newObjects[] = $cartItem;
        }
    }
    public function registerDirty(CartItem &$cartItem)
    {
        if (!in_array($cartItem, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $cartItem;
        }
    }
    public function registerDeleted(CartItem &$cartItem)
    {
        if (!in_array($cartItem, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $cartItem;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->cartItemMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->cartItemMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->cartItemMapper->delete($entity->getId());
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
