<?php
use PostApi\modules\auth\app\controllers\TokenController;
use PostApi\modules\auth\app\http\middlewares\GateForPermissionsMiddleware;
use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::MANAGER , RoleTypes::SECURITY , RoleTypes::USER]);
$permissionsGateMiddleware = new GateForPermissionsMiddleware(['display products']);
$throttleMiddleware = new ThrottleMiddleware(10, 60);

$getTokenRoute = new Route(Urls::transformRouteUrl("/tokens/:id") , HttpMethodsType::GET , TokenController::class , 'get');
$getTokenRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($getTokenRoute);
$middlewareRoutes->addRoute($getTokenRoute);

$getTokensRoute = new Route(Urls::transformRouteUrl("/tokens/") , HttpMethodsType::GET , TokenController::class , 'index');
$getTokensRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware)->addMiddleware($permissionsGateMiddleware);
$router->addRoute($getTokensRoute);
$middlewareRoutes->addRoute($getTokensRoute);

$createTokenRoute = new Route(Urls::transformRouteUrl("/tokens/create"), HttpMethodsType::POST , TokenController::class , 'create');
$createTokenRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($createTokenRoute);
$middlewareRoutes->addRoute($createTokenRoute);

$updateTokenRoute = new Route(Urls::transformRouteUrl("/tokens/:id") , HttpMethodsType::PUT , TokenController::class , 'update');
$updateTokenRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($updateTokenRoute);
$middlewareRoutes->addRoute($updateTokenRoute);

$deleteTokenRoute = new Route(Urls::transformRouteUrl("/tokens/:id") , HttpMethodsType::DELETE , TokenController::class , 'delete');
$deleteTokenRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($deleteTokenRoute);
$middlewareRoutes->addRoute($deleteTokenRoute);
