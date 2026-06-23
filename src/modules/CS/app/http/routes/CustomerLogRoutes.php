<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\CS\app\controllers\CustomerLogController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::CS , RoleTypes::MANAGER , RoleTypes::USER]);

$listRoute = new Route(Urls::transformRouteUrl("/customer-logs/"), HttpMethodsType::GET, CustomerLogController::class, 'index');
$listRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($listRoute);
$router->addRoute($listRoute);

$createRoute = new Route(Urls::transformRouteUrl("/customer-logs/create"), HttpMethodsType::POST, CustomerLogController::class, 'create');
$createRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createRoute);
$router->addRoute($createRoute);

$getRoute = new Route(Urls::transformRouteUrl("/customer-logs/:id"), HttpMethodsType::GET, CustomerLogController::class, 'get');
$getRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getRoute);
$router->addRoute($getRoute);

$updateRoute = new Route(Urls::transformRouteUrl("/customer-logs/:id"), HttpMethodsType::PUT, CustomerLogController::class, 'update');
$updateRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateRoute);
$router->addRoute($updateRoute);

$deleteRoute = new Route(Urls::transformRouteUrl("/customer-logs/:id"), HttpMethodsType::DELETE, CustomerLogController::class, 'delete');
$deleteRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteRoute);
$router->addRoute($deleteRoute);
