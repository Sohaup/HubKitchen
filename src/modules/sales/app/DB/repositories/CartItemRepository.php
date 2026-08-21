<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\CartItemMapper;
use PostApi\modules\sales\domain\entities\CartItem;
use PostApi\shared\templates\DB_Trait;

class CartItemRepository
{
    private CartItemMapper $cartItemMapper;
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
        $this->cartItemMapper = new CartItemMapper($this->dataBase);
    }

    public function findOne(int $id)
    {
        return $this->cartItemMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->cartItemMapper->findAll();
    }

    public function findBy(array $critiria)
    {
        return $this->cartItemMapper->findBy($critiria);
    }

    public function create(CartItem $cartItem)
    {
        $this->cartItemMapper->create($cartItem);
    }

    public function update(CartItem $cartItem)
    {
        $this->cartItemMapper->update($cartItem);
    }

    public function delete(int $id)
    {
        $this->cartItemMapper->delete($id);
    }
}
