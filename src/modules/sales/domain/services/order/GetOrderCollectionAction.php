<?php

namespace PostApi\modules\sales\domain\services\order;

use PostApi\modules\sales\app\DB\repositories\OrderRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetOrderCollectionAction
{
    public static function execute()
    {
        $orderRepository = new OrderRepository();
        $orders = $orderRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($orders);
        return $serin;
    }
}
