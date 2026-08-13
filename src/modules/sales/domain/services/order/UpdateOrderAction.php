<?php

namespace PostApi\modules\sales\domain\services\order;

use PostApi\modules\sales\app\DB\repositories\OrderRepository;
use PostApi\modules\sales\domain\entities\Order;

class UpdateOrderAction
{
    public static function execute(Order $order)
    {
        $orderRepository = new OrderRepository();
        $orderRepository->update($order);
    }
}
