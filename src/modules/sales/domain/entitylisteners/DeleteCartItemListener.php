<?php

namespace PostApi\modules\sales\domain\entitylisteners;

use Override;
use PostApi\modules\sales\app\DB\repositories\CartRepository;
use PostApi\modules\sales\domain\services\cartitem\DeleteCartItemAction;
use SplObserver;
use SplSubject;

class DeleteCartItemListener implements SplObserver
{
    #[Override]
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof DeleteCartItemAction && $subject->getEvent() == "deleted") {
            $cartRepo = new CartRepository();
            $cartItem = $subject->getCartItem();
            $cart = $cartRepo->findOne($cartItem->getCart()->getId());
            $product = $cartItem->getProduct();
            $quantity = $cartItem->getQuantity();
            $price = $product->getPrice();
            $totalCartPrice = $cart->getPrice();
            $productPrice = bcmul((string)$price , (string)$quantity , 1);
            $sum = bcsub((string)$totalCartPrice , $productPrice , 1);           
            $cart->setPrice($sum);
            $cartRepo->update($cart);
        }
    }
}
