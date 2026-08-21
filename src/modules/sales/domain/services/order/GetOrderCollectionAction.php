<?php

namespace PostApi\modules\sales\domain\services\order;

use PostApi\modules\sales\app\DB\repositories\OrderRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetOrderCollectionAction
{
    public static function execute(array $items = null)
    {
        $orderRepository = new OrderRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $orderRepository->findAll());
        return $serin;
    }
}
