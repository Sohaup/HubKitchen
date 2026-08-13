<?php
namespace PostApi\modules\inovice\app\DB\repositories;

use PostApi\modules\inovice\app\DB\models\OrderMapper;
use PostApi\modules\inovice\domain\entities\Order;
use PostApi\shared\templates\DB_Trait;

class OrderRepository
{
    use DB_Trait;
    private OrderMapper $orderMapper;
    public function __construct()
    {
        $this->initialize();
        $this->orderMapper = new OrderMapper($this->dataBase);
    }

    public function findOne(string $id) {
        return $this->orderMapper->findOne($id);
    }

    public function findAll() {
        return $this->orderMapper->findAll();
    }

    public function create(Order $order) {
        $this->orderMapper->insert($order);
    }

    public function update(Order $order) {
        $this->orderMapper->update($order);
    }

    public function delete(string $id) {
        $this->orderMapper->delete($id);
    }
}
