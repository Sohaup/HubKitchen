<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\CS\app\controllers\StatusController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::CS , RoleTypes::MANAGER , RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(20, 60);

$getStatusesRoute = new Route(Urls::transformRouteUrl("/statuses/"), HttpMethodsType::GET, StatusController::class, 'index');
$getStatusesRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getStatusesRoute);
$router->addRoute($getStatusesRoute);

$createStatusRoute = new Route(Urls::transformRouteUrl("/statuses/create"), HttpMethodsType::POST, StatusController::class, 'create');
$createStatusRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createStatusRoute);
$router->addRoute($createStatusRoute);

$getStatusRoute = new Route(Urls::transformRouteUrl("/statuses/:id"), HttpMethodsType::GET, StatusController::class, 'get');
$getStatusRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getStatusRoute);
$router->addRoute($getStatusRoute);

$updateStatusRoute = new Route(Urls::transformRouteUrl("/statuses/:id"), HttpMethodsType::PUT, StatusController::class, 'update');
$updateStatusRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateStatusRoute);
$router->addRoute($updateStatusRoute);

$deleteStatusRoute = new Route(Urls::transformRouteUrl("/statuses/:id"), HttpMethodsType::DELETE, StatusController::class, 'delete');
$deleteStatusRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteStatusRoute);
$router->addRoute($deleteStatusRoute);
