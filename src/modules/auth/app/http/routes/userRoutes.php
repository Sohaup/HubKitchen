<?php

use PostApi\modules\auth\app\controllers\UserController;
use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\shared\app\http\proxies\ProxyMiddlewareForRoute;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\routes\Route\RouteCollection;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleWare = new GateMiddleware(['HR' , 'CS' , 'Maneger' , "Sales" , "user"]);
// $middlewareRoutes = new RouteCollection();

$getUsersRoute = new Route(Urls::transformRouteUrl("/users/") , HttpMethodsType::GET , UserController::class , 'index');
$getUsersRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($getUsersRoute);
$middlewareRoutes->addRoute($getUsersRoute);

$getUserRoute = new Route(Urls::transformRouteUrl("/users/:id") , HttpMethodsType::GET , UserController::class , 'get');
$getUserRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($getUserRoute);
$middlewareRoutes->addRoute($getUserRoute);

$createUserRoute = new Route(Urls::transformRouteUrl("/users/create") , HttpMethodsType::POST , UserController::class , 'create');
$createUserRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($createUserRoute);
$middlewareRoutes->addRoute($createUserRoute);

$gateMiddleWareForCrud = new GateMiddleware(['Maneger' , 'user' , 'CS']);

$updateUserRoute = new Route(Urls::transformRouteUrl("/users/:id") , HttpMethodsType::PUT , UserController::class , 'update');
$updateUserRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWareForCrud);
$router->addRoute($updateUserRoute);
$middlewareRoutes->addRoute($updateUserRoute);

$deleteUserRoute = new Route(Urls::transformRouteUrl("/users/:id") , HttpMethodsType::DELETE , UserController::class , 'delete');
$deleteUserRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWareForCrud);
$router->addRoute($deleteUserRoute);
$middlewareRoutes->addRoute($deleteUserRoute);

// $proxyMiddleware = new ProxyMiddlewareForRoute($middlewareRoutes);
// $proxyMiddleware->execute($request);

