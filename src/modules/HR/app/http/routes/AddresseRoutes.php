<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\AddresseController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER , RoleTypes::USER]);

$getAddresseRoute = new Route(Urls::transformRouteUrl("/addresses/:id"), HttpMethodsType::GET, AddresseController::class, "get");
$getAddresseRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getAddresseRoute);
$router->addRoute($getAddresseRoute);

$getAddressesRoute = new Route(Urls::transformRouteUrl("/addresses/"), HttpMethodsType::GET, AddresseController::class, "index");
$getAddressesRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getAddressesRoute);
$router->addRoute($getAddressesRoute);

$createAddresseRoute = new Route(Urls::transformRouteUrl("/addresses/create"), HttpMethodsType::POST, AddresseController::class, "create");
$createAddresseRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createAddresseRoute);
$router->addRoute($createAddresseRoute);

$updateAddresseRoute = new Route(Urls::transformRouteUrl("/addresses/:id"), HttpMethodsType::PUT, AddresseController::class, "update");
$updateAddresseRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateAddresseRoute);
$router->addRoute($updateAddresseRoute);

$deleteAddresseRoute = new Route(Urls::transformRouteUrl("/addresses/:id"), HttpMethodsType::DELETE, AddresseController::class, "delete");
$deleteAddresseRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteAddresseRoute);
$router->addRoute($deleteAddresseRoute);
