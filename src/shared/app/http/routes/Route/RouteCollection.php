<?php
namespace PostApi\shared\app\http\routes\Route;

use IteratorAggregate;
use Traversable;

class RouteCollection implements IteratorAggregate {
    public array $routes = [];
    public function addRoute(Route $route) {
        $this->routes[] = $route;
    }
    /**
     * @return Traversable<Route>
    **/
    public function getIterator(): Traversable
    {
       foreach ($this->routes as $route) {
            yield $route;
       }
    }
}