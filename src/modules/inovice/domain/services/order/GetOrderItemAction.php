<?php

namespace PostApi\modules\inovice\domain\services\order;

use PostApi\modules\inovice\app\DB\repositories\OrderRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetOrderItemAction
{
    public static function execute(string $id)
    {
        $repo = new OrderRepository();
        $order = $repo->findOne($id);
        return SerializeToSerin::serialize($order);
    }
}
