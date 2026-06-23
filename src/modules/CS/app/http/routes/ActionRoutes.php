<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\CS\app\controllers\ActionController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::CS , RoleTypes::MANAGER , RoleTypes::USER]);

$getActionsRoute = new Route(Urls::transformRouteUrl("/actions/"), HttpMethodsType::GET, ActionController::class, 'index');
$getActionsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getActionsRoute);
$router->addRoute($getActionsRoute);

$createActionRoute = new Route(Urls::transformRouteUrl("/actions/create"), HttpMethodsType::POST, ActionController::class, 'create');
$createActionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createActionRoute);
$router->addRoute($createActionRoute);

$getActionRoute = new Route(Urls::transformRouteUrl("/actions/:id"), HttpMethodsType::GET, ActionController::class, 'get');
$getActionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getActionRoute);
$router->addRoute($getActionRoute);

$updateActionRoute = new Route(Urls::transformRouteUrl("/actions/:id"), HttpMethodsType::PUT, ActionController::class, 'update');
$updateActionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateActionRoute);
$router->addRoute($updateActionRoute);

$deleteActionRoute = new Route(Urls::transformRouteUrl("/actions/:id"), HttpMethodsType::DELETE, ActionController::class, 'delete');
$deleteActionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteActionRoute);
$router->addRoute($deleteActionRoute);
