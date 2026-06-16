<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\EmployeeController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER , RoleTypes::USER]);

$getEmployeeRoute = new Route(Urls::transformRouteUrl("/employees/:id"), HttpMethodsType::GET, EmployeeController::class, "get");
$getEmployeeRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getEmployeeRoute);
$router->addRoute($getEmployeeRoute);

$getEmployeesRoute = new Route(Urls::transformRouteUrl("/employees/"), HttpMethodsType::GET, EmployeeController::class, "index");
$getEmployeesRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getEmployeesRoute);
$router->addRoute($getEmployeesRoute);

$createEmployeeRoute = new Route(Urls::transformRouteUrl("/employees/create"), HttpMethodsType::POST, EmployeeController::class, "create");
$createEmployeeRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createEmployeeRoute);
$router->addRoute($createEmployeeRoute);

$updateEmployeeRoute = new Route(Urls::transformRouteUrl("/employees/:id"), HttpMethodsType::PUT, EmployeeController::class, "update");
$updateEmployeeRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateEmployeeRoute);
$router->addRoute($updateEmployeeRoute);

$deleteEmployeeRoute = new Route(Urls::transformRouteUrl("/employees/:id"), HttpMethodsType::DELETE, EmployeeController::class, "delete");
$deleteEmployeeRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteEmployeeRoute);
$router->addRoute($deleteEmployeeRoute);
