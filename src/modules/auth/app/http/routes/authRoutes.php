<?php

use PostApi\modules\auth\app\controllers\AuthController;
use PostApi\modules\auth\app\controllers\RolesPermissionController;
use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\shared\app\http\proxies\ProxyMiddlewareForRoute;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\routes\Route\RouteCollection;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";
require_once __DIR__ . "/userRoutes.php";
require_once __DIR__ . "/roleRoutes.php";
require_once __DIR__ . "/permissionRoutes.php";
require_once __DIR__ . "/tokenRoutes.php";


$gateMiddleWare = new GateMiddleware(['Manager' , 'Security']);


$grantPermissionOnRoleRoute = new Route(Urls::transformRouteUrl("/grant/:id") , HttpMethodsType::POST , RolesPermissionController::class , 'grant');
$grantPermissionOnRoleRoute->addMiddleware($guardMiddleWare)->addMiddleware($gateMiddleWare);
$router->addRoute($grantPermissionOnRoleRoute);
$middlewareRoutes->addRoute($grantPermissionOnRoleRoute);

$revokePermissionFromRoleRoute = new Route(Urls::transformRouteUrl("/revoke/:id") , HttpMethodsType::DELETE , RolesPermissionController::class , 'revoke');
$revokePermissionFromRoleRoute->addMiddleware($guardMiddleWare)->addMiddleware($gateMiddleWare);
$router->addRoute($revokePermissionFromRoleRoute);
$middlewareRoutes->addRoute($revokePermissionFromRoleRoute);

$registerRoute = new Route(Urls::transformRouteUrl("/register") , HttpMethodsType::POST , AuthController::class , 'register');
$router->addRoute($registerRoute);

$loginRoute = new Route(Urls::transformRouteUrl("/login") , HttpMethodsType::POST ,AuthController::class , 'logIn' );
$router->addRoute($loginRoute);

$loginWithGoogleRoute = new Route(Urls::transformRouteUrl("/login/google") , HttpMethodsType::GET , AuthController::class , 'loginWithGoogle');
$router->addRoute($loginWithGoogleRoute);

$proxyMiddleware = new ProxyMiddlewareForRoute($middlewareRoutes);
$proxyMiddleware->execute($request);


