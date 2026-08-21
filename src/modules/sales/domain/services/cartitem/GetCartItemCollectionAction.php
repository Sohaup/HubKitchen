<?php

namespace PostApi\modules\sales\domain\services\cartitem;

use PostApi\modules\sales\app\DB\repositories\CartItemRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCartItemCollectionAction
{
    public static function execute(array $items = null)
    {
        $cartItemRepository = new CartItemRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $cartItemRepository->findAll());
        return $serin;
    }
}
