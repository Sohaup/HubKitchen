<?php

namespace PostApi\modules\sales\domain\aggregators;

use IteratorAggregate;
use Traversable;
use Override;
use PostApi\modules\sales\domain\entities\CartItem;

class CartItems implements IteratorAggregate
{
    /** @var array<CartItem> */
    public array $cartItems = [];
    public function addCartItem(CartItem $cartItem)
    {
        $this->cartItems[] = $cartItem;
    }
    public function removeCartItem(CartItem $cartItem)
    {
        $index = array_search($cartItem, $this->cartItems);
        if ($index) {
            unset($this->cartItems[$index]);
        }
    }
    #[Override]
    public function getIterator(): Traversable
    {   
        foreach($this->cartItems as $cartItem) {
            yield $cartItem;
        }
    }
}
