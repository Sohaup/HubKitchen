<?php

namespace PostApi\modules\inovice\domain\services\order;

use PostApi\modules\inovice\app\DB\repositories\OrderRepository;
use PostApi\modules\inovice\domain\entities\Prucher;
use PostApi\shared\app\http\requests\Request;

class UpdateOrderAction
{
    public static function execute(string $id , array $params)
    {       
        $repo = new OrderRepository();
        $order = $repo->findOne($id);
        if (!$order) {
            throw new \Exception("order not found");
        }
        if (isset($params['prucher_id'])) {
            $prucher = new Prucher();
            $prucher->setId($params['prucher_id']);
            $order->setPrucher($prucher);
        }
        $repo->update($order);
        return $order;
    }
}
