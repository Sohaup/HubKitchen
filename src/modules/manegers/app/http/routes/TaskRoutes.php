<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\manegers\app\controllers\TaskController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::MANAGER , RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(100, 60);

$getItemsRoute = new Route(Urls::transformRouteUrl("/tasks/"), HttpMethodsType::GET, TaskController::class, 'index');
$getItemsRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getItemsRoute);
$router->addRoute($getItemsRoute);

$createRoute = new Route(Urls::transformRouteUrl("/tasks/create"), HttpMethodsType::POST, TaskController::class, 'create');
$createRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createRoute);
$router->addRoute($createRoute);

$getRoute = new Route(Urls::transformRouteUrl("/tasks/:id"), HttpMethodsType::GET, TaskController::class, 'get');
$getRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getRoute);
$router->addRoute($getRoute);

$updateRoute = new Route(Urls::transformRouteUrl("/tasks/:id"), HttpMethodsType::PUT, TaskController::class, 'update');
$updateRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateRoute);
$router->addRoute($updateRoute);

$deleteRoute = new Route(Urls::transformRouteUrl("/tasks/:id"), HttpMethodsType::DELETE, TaskController::class, 'delete');
$deleteRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteRoute);
$router->addRoute($deleteRoute);
