<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\sales\app\controllers\LeadController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::SALES, RoleTypes::MANAGER, RoleTypes::USER]);

$getLeadRoute = new Route(Urls::transformRouteUrl("/leads/:id"), HttpMethodsType::GET, LeadController::class, "get");
$getLeadRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getLeadRoute);
$router->addRoute($getLeadRoute);

$getLeadsRoute = new Route(Urls::transformRouteUrl("/leads/"), HttpMethodsType::GET, LeadController::class, "index");
$getLeadsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getLeadsRoute);
$router->addRoute($getLeadsRoute);

$createLeadRoute = new Route(Urls::transformRouteUrl("/leads/create"), HttpMethodsType::POST, LeadController::class, "create");
$createLeadRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createLeadRoute);
$router->addRoute($createLeadRoute);

$updateLeadRoute = new Route(Urls::transformRouteUrl("/leads/:id"), HttpMethodsType::PUT, LeadController::class, "update");
$updateLeadRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateLeadRoute);
$router->addRoute($updateLeadRoute);

$deleteLeadRoute = new Route(Urls::transformRouteUrl("/leads/:id"), HttpMethodsType::DELETE, LeadController::class, "delete");
$deleteLeadRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteLeadRoute);
$router->addRoute($deleteLeadRoute);
