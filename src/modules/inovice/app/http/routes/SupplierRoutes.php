<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\inovice\app\controllers\SupplierController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::CS, RoleTypes::MANAGER, RoleTypes::USER]);

$getSuppliersRoute = new Route(Urls::transformRouteUrl("/suppliers/"), HttpMethodsType::GET, SupplierController::class, 'index');
$getSuppliersRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getSuppliersRoute);
$router->addRoute($getSuppliersRoute);

$createSupplierRoute = new Route(Urls::transformRouteUrl("/suppliers/create"), HttpMethodsType::POST, SupplierController::class, 'create');
$createSupplierRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createSupplierRoute);
$router->addRoute($createSupplierRoute);

$getSupplierRoute = new Route(Urls::transformRouteUrl("/suppliers/:id"), HttpMethodsType::GET, SupplierController::class, 'get');
$getSupplierRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getSupplierRoute);
$router->addRoute($getSupplierRoute);

$updateSupplierRoute = new Route(Urls::transformRouteUrl("/suppliers/:id"), HttpMethodsType::PUT, SupplierController::class, 'update');
$updateSupplierRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateSupplierRoute);
$router->addRoute($updateSupplierRoute);

$deleteSupplierRoute = new Route(Urls::transformRouteUrl("/suppliers/:id"), HttpMethodsType::DELETE, SupplierController::class, 'delete');
$deleteSupplierRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteSupplierRoute);
$router->addRoute($deleteSupplierRoute);
