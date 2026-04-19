<?php

use PostApi\modules\auth\app\controllers\RoleController;
use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\shared\app\http\proxies\ProxyMiddlewareForRoute;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\routes\Route\RouteCollection;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleWare = new GuardMiddleware();
$gateMiddleware = new GateMiddleware(['Manager' , 'HR' , 'Security']);


$getRolesRoute = new Route(Urls::transformRouteUrl("/roles/") , HttpMethodsType::GET , RoleController::class , 'index');
$getRolesRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($getRolesRoute);
$middlewareRoutes->addRoute($getRolesRoute);

$getRoleRoute = new Route(Urls::transformRouteUrl("/roles/:id") , HttpMethodsType::GET , RoleController::class , 'get');
$getRoleRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($getRoleRoute);
$middlewareRoutes->addRoute($getRoleRoute);

$createRoleRoute = new Route(Urls::transformRouteUrl("/roles/create") , HttpMethodsType::POST , RoleController::class , 'create');
$createRoleRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($createRoleRoute);
$middlewareRoutes->addRoute($createRoleRoute);

$updateRoleRoute = new Route(Urls::transformRouteUrl("/roles/:id") , HttpMethodsType::PUT , RoleController::class , 'update');
$updateRoleRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($updateRoleRoute);
$middlewareRoutes->addRoute($updateRoleRoute);

$deleteRoleRoute = new Route(Urls::transformRouteUrl("/roles/:id") , HttpMethodsType::DELETE , RoleController::class , 'delete');
$deleteRoleRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($deleteRoleRoute);
$middlewareRoutes->addRoute($deleteRoleRoute);

// $proxyMiddleware = new ProxyMiddlewareForRoute($middlewareRoutes);


