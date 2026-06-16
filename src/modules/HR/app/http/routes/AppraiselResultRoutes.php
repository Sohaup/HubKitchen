<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\AppraiselResultController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER, RoleTypes::USER]);

$getResultRoute = new Route(Urls::transformRouteUrl("/appraisel-results/:id"), HttpMethodsType::GET, AppraiselResultController::class, "get");
$getResultRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getResultRoute);
$router->addRoute($getResultRoute);

$getResultsRoute = new Route(Urls::transformRouteUrl("/appraisel-results/"), HttpMethodsType::GET, AppraiselResultController::class, "index");
$getResultsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getResultsRoute);
$router->addRoute($getResultsRoute);

$createResultRoute = new Route(Urls::transformRouteUrl("/appraisel-results/create"), HttpMethodsType::POST, AppraiselResultController::class, "create");
$createResultRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createResultRoute);
$router->addRoute($createResultRoute);

$updateResultRoute = new Route(Urls::transformRouteUrl("/appraisel-results/:id"), HttpMethodsType::PUT, AppraiselResultController::class, "update");
$updateResultRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateResultRoute);
$router->addRoute($updateResultRoute);

$deleteResultRoute = new Route(Urls::transformRouteUrl("/appraisel-results/:id"), HttpMethodsType::DELETE, AppraiselResultController::class, "delete");
$deleteResultRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteResultRoute);
$router->addRoute($deleteResultRoute);
