<?php

use PostApi\modules\auth\app\controllers\UserController;
use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleWare = new GateMiddleware([RoleTypes::HR , RoleTypes::CS , RoleTypes::MANAGER , RoleTypes::SALES , RoleTypes::MARKETING , RoleTypes::USER]);
$throttleMiddleWare = new ThrottleMiddleware(50 , 60);

$getUsersRoute = new Route(Urls::transformRouteUrl("/users/") , HttpMethodsType::GET , UserController::class , 'index');
$getUsersRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare)->addMiddleware($throttleMiddleWare);
$router->addRoute($getUsersRoute);
$middlewareRoutes->addRoute($getUsersRoute);

$getUserRoute = new Route(Urls::transformRouteUrl("/users/:id") , HttpMethodsType::GET , UserController::class , 'get');
$getUserRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare)->addMiddleware($throttleMiddleWare);
$router->addRoute($getUserRoute);
$middlewareRoutes->addRoute($getUserRoute);

$createUserRoute = new Route(Urls::transformRouteUrl("/users/create") , HttpMethodsType::POST , UserController::class , 'create');
$createUserRoute->addMiddleware($throttleMiddleWare);
$router->addRoute($createUserRoute);
// $middlewareRoutes->addRoute($createUserRoute);

$gateMiddleWareForCrud = new GateMiddleware([RoleTypes::MANAGER , RoleTypes::USER ,RoleTypes::CS]);

$updateUserRoute = new Route(Urls::transformRouteUrl("/users/:id") , HttpMethodsType::POST , UserController::class , 'update');
$updateUserRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWareForCrud);
$router->addRoute($updateUserRoute);
$middlewareRoutes->addRoute($updateUserRoute);

$deleteUserRoute = new Route(Urls::transformRouteUrl("/users/:id") , HttpMethodsType::DELETE , UserController::class , 'delete');
$deleteUserRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWareForCrud);
$router->addRoute($deleteUserRoute);
$middlewareRoutes->addRoute($deleteUserRoute);
