<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\ApplicationController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER , RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(100, 60);

$getApplicationRoute = new Route(Urls::transformRouteUrl("/applications/:id"), HttpMethodsType::GET, ApplicationController::class, "get");
$getApplicationRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getApplicationRoute);
$router->addRoute($getApplicationRoute);

$getApplicationsRoute = new Route(Urls::transformRouteUrl("/applications/"), HttpMethodsType::GET, ApplicationController::class, "index");
$getApplicationsRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getApplicationsRoute);
$router->addRoute($getApplicationsRoute);

$createApplicationRoute = new Route(Urls::transformRouteUrl("/applications/create"), HttpMethodsType::POST, ApplicationController::class, "create");
$createApplicationRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createApplicationRoute);
$router->addRoute($createApplicationRoute);

$updateApplicationRoute = new Route(Urls::transformRouteUrl("/applications/:id"), HttpMethodsType::POST, ApplicationController::class, "update");
$updateApplicationRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateApplicationRoute);
$router->addRoute($updateApplicationRoute);

$deleteApplicationRoute = new Route(Urls::transformRouteUrl("/applications/:id"), HttpMethodsType::DELETE, ApplicationController::class, "delete");
$deleteApplicationRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteApplicationRoute);
$router->addRoute($deleteApplicationRoute);
