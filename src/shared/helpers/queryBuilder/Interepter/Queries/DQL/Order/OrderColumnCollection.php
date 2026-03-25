<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Order;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class OrderColumnCollection implements IteratorAggregate
{
    public array $orderColumns = [];
    public function addColumn(OrderColumn $column)
    {
       $this->orderColumns[] = $column;
    }
    /** 
     * @return Traversable<OrderColumn>
     **/
    public function getIterator(): Traversable
    {
        foreach ($this->orderColumns as $orderColumn) {
            yield $orderColumn;
        }
    }
}
