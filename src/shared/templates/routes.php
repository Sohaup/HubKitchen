<?php

use PostApi\shared\app\http\proxies\ProxyMiddleware;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\routes\Route\RouteCollection;
use PostApi\shared\app\http\routes\Router;

$router = new Router();
$request = new Request();
$proxy = new ProxyMiddleware();
$middlewareRoutes = new RouteCollection();
