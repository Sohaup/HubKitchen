<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\OrderMapper;
use PostApi\modules\sales\domain\entities\Order;
use PostApi\shared\templates\DB_Trait;

class OrderRepository
{
    private OrderMapper $orderMapper;
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
        $this->orderMapper = new OrderMapper($this->dataBase);
    }

    public function findOne(string $id)
    {
        return $this->orderMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->orderMapper->findAll();
    }

    public function create(Order $order)
    {
        $this->orderMapper->create($order);
    }

    public function update(Order $order)
    {
        $this->orderMapper->update($order);
    }

    public function delete(string $id)
    {
        $this->orderMapper->delete($id);
    }
}
