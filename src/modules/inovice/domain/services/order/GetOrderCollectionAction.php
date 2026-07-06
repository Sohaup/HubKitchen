<?php

namespace PostApi\modules\inovice\domain\services\order;

use PostApi\modules\inovice\app\DB\repositories\OrderRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetOrderCollectionAction
{
    public static function execute()
    {
        $repo = new OrderRepository();
        $orders = $repo->findAll();
        return SerializeToSerin::serializeCollection($orders);
    }
}
