<?php

namespace PostApi\modules\sales\domain\services\order;

use Override;
use PostApi\modules\sales\app\DB\repositories\OrderRepository;
use PostApi\modules\sales\domain\entities\Order;
use PostApi\modules\sales\domain\entitylisteners\DelecteOrderListener;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class DeleteOrderAction implements SplSubject
{
    private SplObjectStorage $observers;
    private string $deleteOrderEvent = "";
    private Order $order;
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
        $this->attach(new DelecteOrderListener());
    }
    public function execute(string $id)
    {
        $orderRepository = new OrderRepository();
        $order = $orderRepository->findOne($id);
        if ($order) {
            $orderRepository->delete($id);
            $this->deleteOrderEvent = "deleted";
            $this->order = $order;
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
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    public function getEvent(): string
    {
        return $this->deleteOrderEvent;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
