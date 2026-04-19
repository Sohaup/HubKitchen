<?php
use PostApi\modules\auth\app\controllers\PermissionController;
use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\shared\app\http\proxies\ProxyMiddlewareForRoute;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\routes\Route\RouteCollection;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::MANAGER->value , RoleTypes::USER->value]);


$getPermissionsRoute = new Route(Urls::transformRouteUrl("/permissions/") , HttpMethodsType::GET , PermissionController::class , 'index');
$getPermissionsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($getPermissionsRoute);
$middlewareRoutes->addRoute($getPermissionsRoute);

$getPermissionRoute = new Route(Urls::transformRouteUrl("/permissions/:id") , HttpMethodsType::GET , PermissionController::class , 'get');
$getPermissionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($getPermissionRoute);
$middlewareRoutes->addRoute($getPermissionRoute);

$createPermissionRoute = new Route(Urls::transformRouteUrl("/permissions/create") , HttpMethodsType::POST , PermissionController::class , 'create');
$createPermissionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($createPermissionRoute);
$middlewareRoutes->addRoute($createPermissionRoute);

$updatePermissionRoute = new Route(Urls::transformRouteUrl("/permissions/:id") , HttpMethodsType::PUT , PermissionController::class , 'update');
$updatePermissionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($updatePermissionRoute);
$middlewareRoutes->addRoute($updatePermissionRoute);

$deletePermissionRoute = new Route(Urls::transformRouteUrl("/permissions/:id") , HttpMethodsType::DELETE , PermissionController::class , 'delete');
$deletePermissionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($deletePermissionRoute);
$middlewareRoutes->addRoute($deletePermissionRoute);

// $proxyMiddlewre = new ProxyMiddlewareForRoute($middlewareRoutes);
