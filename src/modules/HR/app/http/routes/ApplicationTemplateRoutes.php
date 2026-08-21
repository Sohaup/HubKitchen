<?php

namespace PostApi\modules\HR\app\http\routes;

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\ApplicationTemplateController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR, RoleTypes::MANAGER , RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(100, 60);

$getTemplateRoute = new Route(Urls::transformRouteUrl("/application-templates/:id"), HttpMethodsType::GET, ApplicationTemplateController::class, "get");
$getTemplateRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getTemplateRoute);
$router->addRoute($getTemplateRoute);

$getTemplatesRoute = new Route(Urls::transformRouteUrl("/application-templates/"), HttpMethodsType::GET, ApplicationTemplateController::class, "index");
$getTemplatesRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getTemplatesRoute);
$router->addRoute($getTemplatesRoute);

$createTemplateRoute = new Route(Urls::transformRouteUrl("/application-templates/create"), HttpMethodsType::POST, ApplicationTemplateController::class, "create");
$createTemplateRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createTemplateRoute);
$router->addRoute($createTemplateRoute);

$updateTemplateRoute = new Route(Urls::transformRouteUrl("/application-templates/:id"), HttpMethodsType::PUT, ApplicationTemplateController::class, "update");
$updateTemplateRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateTemplateRoute);
$router->addRoute($updateTemplateRoute);

$deleteTemplateRoute = new Route(Urls::transformRouteUrl("/application-templates/:id"), HttpMethodsType::DELETE, ApplicationTemplateController::class, "delete");
$deleteTemplateRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteTemplateRoute);
$router->addRoute($deleteTemplateRoute);
