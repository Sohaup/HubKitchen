<?php

namespace PostApi\modules\inovice\domain\services\order;

use PostApi\modules\inovice\app\DB\repositories\OrderRepository;
use PostApi\modules\inovice\domain\entities\Order;
use PostApi\modules\inovice\domain\entities\Prucher;
use PostApi\shared\app\http\requests\Request;

class CreateOrderAction
{
    public static function execute(array $params): Order
    {       
        $order = new Order();
        $prucher = new Prucher();
        $prucher->setId($params['prucher_id'] ?? '');
        $order->setPrucher($prucher);
        $order->setCreatedAt(date('Y-m-d H:i:s'));
        $repo = new OrderRepository();
        $repo->create($order);
        return $order;
    }
}
