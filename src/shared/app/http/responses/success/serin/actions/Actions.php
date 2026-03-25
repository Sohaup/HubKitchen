<?php 
namespace PostApi\shared\app\http\responses\success\serin\actions;

use IteratorAggregate;
use Traversable;

class Actions implements IteratorAggregate {
    public array $actions = [];
    public function addAction(Action $action) {
        $this->actions[] = $action;
    }
    /**
     * @return Traversable<Action>
     */
    public function getIterator(): Traversable
    {
       foreach ($this->actions as $action) {
            yield $action; 
       }
    }
}