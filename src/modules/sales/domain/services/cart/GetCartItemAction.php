<?php

namespace PostApi\modules\sales\domain\services\cart;

use PostApi\modules\sales\app\DB\repositories\CartRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCartItemAction
{
    public static function execute(string $id)
    {
        $cartRepository = new CartRepository();
        $cart = $cartRepository->findOne($id);
        $serin = SerializeToSerin::serialize($cart);
        return $serin;
    }
}
