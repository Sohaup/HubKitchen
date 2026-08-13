<?php

namespace PostApi\modules\sales\domain\services\cartitem;

use PostApi\modules\sales\app\DB\repositories\CartItemRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCartItemCollectionAction
{
    public static function execute()
    {
        $cartItemRepository = new CartItemRepository();
        $cartItems = $cartItemRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($cartItems);
        return $serin;
    }
}
