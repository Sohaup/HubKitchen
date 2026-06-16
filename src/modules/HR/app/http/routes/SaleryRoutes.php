<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\SaleryController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER, RoleTypes::USER]);

$getSaleryRoute = new Route(Urls::transformRouteUrl("/selaries/:id"), HttpMethodsType::GET, SaleryController::class, "get");
$getSaleryRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getSaleryRoute);
$router->addRoute($getSaleryRoute);

$getSelariesRoute = new Route(Urls::transformRouteUrl("/selaries/"), HttpMethodsType::GET, SaleryController::class, "index");
$getSelariesRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getSelariesRoute);
$router->addRoute($getSelariesRoute);

$createSaleryRoute = new Route(Urls::transformRouteUrl("/selaries/create"), HttpMethodsType::POST, SaleryController::class, "create");
$createSaleryRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createSaleryRoute);
$router->addRoute($createSaleryRoute);

$updateSaleryRoute = new Route(Urls::transformRouteUrl("/selaries/:id"), HttpMethodsType::PUT, SaleryController::class, "update");
$updateSaleryRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateSaleryRoute);
$router->addRoute($updateSaleryRoute);

$deleteSaleryRoute = new Route(Urls::transformRouteUrl("/selaries/:id"), HttpMethodsType::DELETE, SaleryController::class, "delete");
$deleteSaleryRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteSaleryRoute);
$router->addRoute($deleteSaleryRoute);
