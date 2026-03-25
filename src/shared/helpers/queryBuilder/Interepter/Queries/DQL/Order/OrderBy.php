<?php

namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Order;

class OrderBy
{
    private string $orderQuery = " ORDER BY ";
    public function __construct(public OrderColumnCollection $columns) {
        $this->parseOrder();
    }
    public function parseOrder()
    {
        foreach ($this->columns as $index => $column) {
            if ($index == 0) {
                $this->orderQuery .= $column->interoptOrder();
            } else {
            $this->orderQuery .= " , ";
            $this->orderQuery .= $column->interoptOrder();
            }
        }
    }


    public function getQuery()
    {
        return $this->orderQuery;
    }
}
