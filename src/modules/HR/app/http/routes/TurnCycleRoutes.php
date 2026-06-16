<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\TurnCycleController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER, RoleTypes::USER]);

$getTurnRoute = new Route(Urls::transformRouteUrl("/turn-cycles/:id"), HttpMethodsType::GET, TurnCycleController::class, "get");
$getTurnRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getTurnRoute);
$router->addRoute($getTurnRoute);

$getTurnsRoute = new Route(Urls::transformRouteUrl("/turn-cycles/"), HttpMethodsType::GET, TurnCycleController::class, "index");
$getTurnsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getTurnsRoute);
$router->addRoute($getTurnsRoute);

$createTurnRoute = new Route(Urls::transformRouteUrl("/turn-cycles/create"), HttpMethodsType::POST, TurnCycleController::class, "create");
$createTurnRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createTurnRoute);
$router->addRoute($createTurnRoute);

$updateTurnRoute = new Route(Urls::transformRouteUrl("/turn-cycles/:id"), HttpMethodsType::PUT, TurnCycleController::class, "update");
$updateTurnRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateTurnRoute);
$router->addRoute($updateTurnRoute);

$deleteTurnRoute = new Route(Urls::transformRouteUrl("/turn-cycles/:id"), HttpMethodsType::DELETE, TurnCycleController::class, "delete");
$deleteTurnRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteTurnRoute);
$router->addRoute($deleteTurnRoute);

