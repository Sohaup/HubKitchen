<?php

namespace PostApi\modules\inovice\domain\services\order;

use PostApi\modules\inovice\app\DB\repositories\OrderRepository;

class DeleteOrderAction
{
    public static function execute(string $id)
    {
        $repo = new OrderRepository();
        $repo->delete($id);
    }
}
