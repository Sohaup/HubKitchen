<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\CS\app\controllers\CustomerController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::CS , RoleTypes::MANAGER , RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(20, 60);

$getCustomersRoute = new Route(Urls::transformRouteUrl("/customers/"), HttpMethodsType::GET, CustomerController::class, 'index');
$getCustomersRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCustomersRoute);
$router->addRoute($getCustomersRoute);

$createCustomerRoute = new Route(Urls::transformRouteUrl("/customers/create"), HttpMethodsType::POST, CustomerController::class, 'create');
$createCustomerRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createCustomerRoute);
$router->addRoute($createCustomerRoute);

$getCustomerRoute = new Route(Urls::transformRouteUrl("/customers/:id"), HttpMethodsType::GET, CustomerController::class, 'get');
$getCustomerRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCustomerRoute);
$router->addRoute($getCustomerRoute);

$updateCustomerRoute = new Route(Urls::transformRouteUrl("/customers/:id"), HttpMethodsType::PUT, CustomerController::class, 'update');
$updateCustomerRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateCustomerRoute);
$router->addRoute($updateCustomerRoute);

$deleteCustomerRoute = new Route(Urls::transformRouteUrl("/customers/:id"), HttpMethodsType::DELETE, CustomerController::class, 'delete');
$deleteCustomerRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteCustomerRoute);
$router->addRoute($deleteCustomerRoute);
