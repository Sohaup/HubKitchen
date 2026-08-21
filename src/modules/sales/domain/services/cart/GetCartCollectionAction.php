<?php

namespace PostApi\modules\sales\domain\services\cart;

use PostApi\modules\sales\app\DB\repositories\CartRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCartCollectionAction
{
    public static function execute(array $items = null)
    {
        $cartRepository = new CartRepository();
        $carts = $cartRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($items ?? $carts);
        return $serin;
    }
}
