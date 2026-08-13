<?php

namespace PostApi\modules\sales\domain\services\order;

use PostApi\modules\sales\app\DB\repositories\OrderRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetOrderItemAction
{
    public static function execute(string $id)
    {
        $orderRepository = new OrderRepository();
        $order = $orderRepository->findOne($id);
        $serin = SerializeToSerin::serialize($order);
        return $serin;
    }
}
