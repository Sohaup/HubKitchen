<?php

namespace PostApi\modules\sales\domain\services\cartitem;

use PostApi\modules\sales\app\DB\repositories\CartItemRepository;
use PostApi\modules\sales\domain\entities\CartItem;

class UpdateCartItemAction
{
    public static function execute(CartItem $cartItem)
    {
        $cartItemRepository = new CartItemRepository();
        $cartItemRepository->update($cartItem);
    }
}
