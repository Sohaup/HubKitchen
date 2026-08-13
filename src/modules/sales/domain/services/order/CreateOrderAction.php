<?php

namespace PostApi\modules\sales\domain\services\order;

use Override;
use PostApi\modules\sales\app\DB\repositories\OrderRepository;
use PostApi\modules\sales\domain\entities\Order;
use PostApi\modules\sales\domain\entitylisteners\CreateOrderListener;
use PostApi\shared\helpers\fecade\SerializeToSerin;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class CreateOrderAction implements SplSubject
{
    private SplObjectStorage $observers;
    private string $createOrderEvent = "";
    private Order $order;
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
        $this->attach(new CreateOrderListener());
    }
    public function execute(Order $order)
    {
        $orderRepository = new OrderRepository();
        $orderRepository->create($order);
        $this->createOrderEvent = "created";
        $this->order = $order;
        $this->notify();
        $serin = SerializeToSerin::serialize($order);
        return $serin;
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
        return $this->createOrderEvent;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
