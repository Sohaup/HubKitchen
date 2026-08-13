<?php

namespace PostApi\modules\sales\domain\services\cart;

use PostApi\modules\sales\app\DB\repositories\CartRepository;

class DeleteCartAction
{
    public static function execute(string $id)
    {
        $cartRepository = new CartRepository();
        $cart = $cartRepository->findOne($id);
        if ($cart) {
            $cartRepository->delete($id);
        }
    }
}
