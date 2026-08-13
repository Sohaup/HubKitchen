<?php

namespace PostApi\modules\sales\domain\services\cartitem;

use Override;
use PostApi\modules\sales\app\DB\repositories\CartItemRepository;
use PostApi\modules\sales\domain\entities\CartItem;
use PostApi\modules\sales\domain\entitylisteners\DeleteCartItemListener;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class DeleteCartItemAction implements SplSubject
{
    private SplObjectStorage $observers;
    private string $deleteCartItemEvent = "";
    private CartItem $cartItem;
    public function __construct()
    {
       $this->observers = new SplObjectStorage();
       $this->attach(new DeleteCartItemListener());
    }
    public function execute(int $id) 
    {
        $cartItemRepository = new CartItemRepository();
        $cartItem = $cartItemRepository->findOne($id);
        if ($cartItem) {
            $cartItemRepository->delete($id);
            $this->deleteCartItemEvent = "deleted";
            $this->cartItem = $cartItem;
            $this->notify();
        }
    }

    #[Override]
    public function attach(SplObserver $observer): void
    {
       $this->observers->attach($observer);
    }

    #[Override]
    public function detach(SplObserver $observer): void
    {
       $this->observers->detach($observer);
    }

    #[Override]
    public function notify(): void
    {
        foreach ($this->observers as $observer ) {
            $observer->update($this);
        }
    }

    public function getEvent() : string {
        return $this->deleteCartItemEvent;
    }

    public function getCartItem() : CartItem {
        return $this->cartItem;
    }
}
