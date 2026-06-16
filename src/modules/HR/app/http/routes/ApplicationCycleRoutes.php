<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\ApplicationCycleController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER, RoleTypes::USER]);

$getCycleRoute = new Route(Urls::transformRouteUrl("/application-cycles/:id"), HttpMethodsType::GET, ApplicationCycleController::class, "get");
$getCycleRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCycleRoute);
$router->addRoute($getCycleRoute);

$getCyclesRoute = new Route(Urls::transformRouteUrl("/application-cycles/"), HttpMethodsType::GET, ApplicationCycleController::class, "index");
$getCyclesRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCyclesRoute);
$router->addRoute($getCyclesRoute);

$createCycleRoute = new Route(Urls::transformRouteUrl("/application-cycles/create"), HttpMethodsType::POST, ApplicationCycleController::class, "create");
$createCycleRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createCycleRoute);
$router->addRoute($createCycleRoute);

$updateCycleRoute = new Route(Urls::transformRouteUrl("/application-cycles/:id"), HttpMethodsType::PUT, ApplicationCycleController::class, "update");
$updateCycleRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateCycleRoute);
$router->addRoute($updateCycleRoute);

$deleteCycleRoute = new Route(Urls::transformRouteUrl("/application-cycles/:id"), HttpMethodsType::DELETE, ApplicationCycleController::class, "delete");
$deleteCycleRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteCycleRoute);
$router->addRoute($deleteCycleRoute);
