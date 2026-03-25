<?php

namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Order;

class OrderColumn
{
    public string $order;
    public function __construct(public string $column,  OrderTypes $order) {
        $this->order = strtoupper($order->value);
    }    
    public function interoptOrder() {
        return "{$this->column} {$this->order}";
    }
}
