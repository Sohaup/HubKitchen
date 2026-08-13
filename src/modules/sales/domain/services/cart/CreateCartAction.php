<?php

namespace PostApi\modules\sales\domain\services\cart;

use PostApi\modules\sales\app\DB\repositories\CartRepository;
use PostApi\modules\sales\domain\entities\Cart;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class CreateCartAction
{
    public static function execute(Cart $cart)
    {
        $cartRepository = new CartRepository();
        $cartRepository->create($cart);
        $serin = SerializeToSerin::serialize($cart);
        return $serin;
    }
}
