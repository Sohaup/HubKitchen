<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\PayrollController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER, RoleTypes::USER]);

$getPayrollRoute = new Route(Urls::transformRouteUrl("/payrolls/:id"), HttpMethodsType::GET, PayrollController::class, "get");
$getPayrollRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getPayrollRoute);
$router->addRoute($getPayrollRoute);


$getPayrollsRoute = new Route(Urls::transformRouteUrl("/payrolls/"), HttpMethodsType::GET, PayrollController::class, "index");
$getPayrollsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getPayrollsRoute);
$router->addRoute($getPayrollsRoute);


$createPayrollRoute = new Route(Urls::transformRouteUrl("/payrolls/create"), HttpMethodsType::POST, PayrollController::class, "create");
$createPayrollRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createPayrollRoute);
$router->addRoute($createPayrollRoute);


$updatePayrollRoute = new Route(Urls::transformRouteUrl("/payrolls/:id"), HttpMethodsType::PUT, PayrollController::class, "update");
$updatePayrollRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updatePayrollRoute);
$router->addRoute($updatePayrollRoute);


$deletePayrollRoute = new Route(Urls::transformRouteUrl("/payrolls/:id"), HttpMethodsType::DELETE, PayrollController::class, "delete");
$deletePayrollRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deletePayrollRoute);
$router->addRoute($deletePayrollRoute);

