<?php

namespace PostApi\modules\inovice\domain\services\order;

use PostApi\modules\inovice\app\DB\repositories\OrderRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetOrderCollectionAction
{
    public static function execute(array $orders)
    {
        return SerializeToSerin::serializeCollection($orders);
    }
}
