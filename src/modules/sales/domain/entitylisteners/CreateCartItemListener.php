<?php

namespace PostApi\modules\sales\domain\entitylisteners;

use Override;
use PostApi\modules\sales\app\DB\repositories\CartRepository;
use PostApi\modules\sales\domain\services\cartitem\CreateCartItemAction;
use SplObserver;
use SplSubject;

class CreateCartItemListener implements SplObserver
{
    #[Override]
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof CreateCartItemAction && $subject->getEvent() == "created") {
            $cartRepo = new CartRepository();
            $cartItem = $subject->getCartItem();
            $cart = $cartRepo->findOne($cartItem->getCart()->getId());
            $cartItems = $cart->getCartItems()->cartItems;
            $sum = "0.0";
            foreach ($cartItems as $cartItemData) {
                $product = $cartItemData->getProduct();
                $price = $product->getPrice();
                $quantity = $cartItemData->getQuantity();
                $productPrice = bcmul((string)$price, (string)$quantity, 1);               
                $sum = bcadd($sum, $productPrice, 1);               
            }
            $cart->setPrice($sum);
            $cartRepo->update($cart);
        }
    }
}
