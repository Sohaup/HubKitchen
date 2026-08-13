<?php

namespace PostApi\modules\sales\domain\services\cartitem;

use PostApi\modules\sales\app\DB\repositories\CartItemRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCartItemItemAction
{
    public static function execute(int $id)
    {
        $cartItemRepository = new CartItemRepository();
        $cartItem = $cartItemRepository->findOne($id);
        $serin = SerializeToSerin::serialize($cartItem);
        return $serin;
    }
}
