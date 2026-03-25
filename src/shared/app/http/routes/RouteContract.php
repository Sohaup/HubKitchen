<?php
namespace PostApi\shared\app\http\routes;

use PostApi\shared\app\http\routes\Route\Route;

interface RouteContract {
    public function addRoute(Route $route);
    public function run();
}

