<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\EvolutionCritiriaController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER, RoleTypes::USER]);

$getCritiriaRoute = new Route(Urls::transformRouteUrl("/evolution-critiria/:id"), HttpMethodsType::GET, EvolutionCritiriaController::class, "get");
$getCritiriaRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCritiriaRoute);
$router->addRoute($getCritiriaRoute);

$getCritiriasRoute = new Route(Urls::transformRouteUrl("/evolution-critiria/"), HttpMethodsType::GET, EvolutionCritiriaController::class, "index");
$getCritiriasRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCritiriasRoute);
$router->addRoute($getCritiriasRoute);

$createCritiriaRoute = new Route(Urls::transformRouteUrl("/evolution-critiria/create"), HttpMethodsType::POST, EvolutionCritiriaController::class, "create");
$createCritiriaRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createCritiriaRoute);
$router->addRoute($createCritiriaRoute);

$updateCritiriaRoute = new Route(Urls::transformRouteUrl("/evolution-critiria/:id"), HttpMethodsType::PUT, EvolutionCritiriaController::class, "update");
$updateCritiriaRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateCritiriaRoute);
$router->addRoute($updateCritiriaRoute);

$deleteCritiriaRoute = new Route(Urls::transformRouteUrl("/evolution-critiria/:id"), HttpMethodsType::DELETE, EvolutionCritiriaController::class, "delete");
$deleteCritiriaRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteCritiriaRoute);
$router->addRoute($deleteCritiriaRoute);
