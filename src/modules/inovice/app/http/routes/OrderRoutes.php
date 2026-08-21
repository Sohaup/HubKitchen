<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\inovice\app\controllers\OrderController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::CS, RoleTypes::MANAGER, RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(100, 60);

$getOrdersRoute = new Route(Urls::transformRouteUrl("/orders/"), HttpMethodsType::GET, OrderController::class, 'index');
$getOrdersRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getOrdersRoute);
$router->addRoute($getOrdersRoute);

$createOrderRoute = new Route(Urls::transformRouteUrl("/orders/create"), HttpMethodsType::POST, OrderController::class, 'create');
$createOrderRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createOrderRoute);
$router->addRoute($createOrderRoute);

$getOrderRoute = new Route(Urls::transformRouteUrl("/orders/:id"), HttpMethodsType::GET, OrderController::class, 'get');
$getOrderRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getOrderRoute);
$router->addRoute($getOrderRoute);

$updateOrderRoute = new Route(Urls::transformRouteUrl("/orders/:id"), HttpMethodsType::PUT, OrderController::class, 'update');
$updateOrderRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateOrderRoute);
$router->addRoute($updateOrderRoute);

$deleteOrderRoute = new Route(Urls::transformRouteUrl("/orders/:id"), HttpMethodsType::DELETE, OrderController::class, 'delete');
$deleteOrderRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteOrderRoute);
$router->addRoute($deleteOrderRoute);
