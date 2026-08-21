<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\CartMapper;
use PostApi\modules\sales\domain\entities\Cart;
use PostApi\shared\templates\DB_Trait;

class CartRepository
{
    private CartMapper $cartMapper;
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
        $this->cartMapper = new CartMapper($this->dataBase);
    }

    public function findOne(string $id)
    {
        return $this->cartMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->cartMapper->findAll();
    }

    public function findBy(array $critiria)
    {
        return $this->cartMapper->findBy($critiria);
    }

    public function create(Cart $cart)
    {
        $this->cartMapper->create($cart);
    }

    public function update(Cart $cart)
    {
        $this->cartMapper->update($cart);
    }

    public function delete(string $id)
    {
        $this->cartMapper->delete($id);
    }
}
