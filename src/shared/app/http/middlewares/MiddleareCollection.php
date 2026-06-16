<?php

namespace PostApi\shared\app\http\middlewares;

use IteratorAggregate;
use Traversable;

class MiddleareCollection implements IteratorAggregate
{
    /** @var  Middleware[] $middleware */
    public array $middlewares = [];
    public function addMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /** @return Traversable<Middleware> */
    public function getIterator(): Traversable
    {
        foreach ($this->middlewares as $middleware) {
            yield $middleware;
        }
    }
}
