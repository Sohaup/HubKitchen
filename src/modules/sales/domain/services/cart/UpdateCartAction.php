<?php

namespace PostApi\modules\sales\domain\services\cart;

use PostApi\modules\sales\app\DB\repositories\CartRepository;
use PostApi\modules\sales\domain\entities\Cart;

class UpdateCartAction
{
    public static function execute(Cart $cart)
    {
        $cartRepository = new CartRepository();
        $cartRepository->update($cart);
    }
}
