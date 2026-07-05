<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\manegers\app\controllers\DepartmentController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::MANAGER , RoleTypes::USER]);

$getItemsRoute = new Route(Urls::transformRouteUrl("/manegers-departments/"), HttpMethodsType::GET, DepartmentController::class, 'index');
$getItemsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getItemsRoute);
$router->addRoute($getItemsRoute);

$createRoute = new Route(Urls::transformRouteUrl("/manegers-departments/create"), HttpMethodsType::POST, DepartmentController::class, 'create');
$createRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createRoute);
$router->addRoute($createRoute);

$getRoute = new Route(Urls::transformRouteUrl("/manegers-departments/:id"), HttpMethodsType::GET, DepartmentController::class, 'get');
$getRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getRoute);
$router->addRoute($getRoute);

$updateRoute = new Route(Urls::transformRouteUrl("/manegers-departments/:id"), HttpMethodsType::PUT, DepartmentController::class, 'update');
$updateRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateRoute);
$router->addRoute($updateRoute);

$deleteRoute = new Route(Urls::transformRouteUrl("/manegers-departments/:id"), HttpMethodsType::DELETE, DepartmentController::class, 'delete');
$deleteRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteRoute);
$router->addRoute($deleteRoute);
