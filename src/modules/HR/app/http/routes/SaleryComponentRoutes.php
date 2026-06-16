<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\SaleryComponentController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER, RoleTypes::USER]);

$getComponentRoute = new Route(Urls::transformRouteUrl("/selaryComponents/:id"), HttpMethodsType::GET, SaleryComponentController::class, "get");
$getComponentRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getComponentRoute);
$router->addRoute($getComponentRoute);

$getComponentsRoute = new Route(Urls::transformRouteUrl("/selaryComponents/"), HttpMethodsType::GET, SaleryComponentController::class, "index");
$getComponentsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getComponentsRoute);
$router->addRoute($getComponentsRoute);

$createComponentRoute = new Route(Urls::transformRouteUrl("/selaryComponents/create"), HttpMethodsType::POST, SaleryComponentController::class, "create");
$createComponentRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createComponentRoute);
$router->addRoute($createComponentRoute);

$updateComponentRoute = new Route(Urls::transformRouteUrl("/selaryComponents/:id"), HttpMethodsType::PUT, SaleryComponentController::class, "update");
$updateComponentRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateComponentRoute);
$router->addRoute($updateComponentRoute);

$deleteComponentRoute = new Route(Urls::transformRouteUrl("/selaryComponents/:id"), HttpMethodsType::DELETE, SaleryComponentController::class, "delete");
$deleteComponentRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteComponentRoute);
$router->addRoute($deleteComponentRoute);
