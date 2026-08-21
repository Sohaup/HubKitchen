<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\sales\app\controllers\CartItemController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::SALES, RoleTypes::MANAGER, RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(100, 60); 

$getCartItemRoute = new Route(Urls::transformRouteUrl("/cart-items/:id"), HttpMethodsType::GET, CartItemController::class, "get");
$getCartItemRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCartItemRoute);
$router->addRoute($getCartItemRoute);

$getCartItemsRoute = new Route(Urls::transformRouteUrl("/cart-items/"), HttpMethodsType::GET, CartItemController::class, "index");
$getCartItemsRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCartItemsRoute);
$router->addRoute($getCartItemsRoute);

$createCartItemRoute = new Route(Urls::transformRouteUrl("/cart-items/create"), HttpMethodsType::POST, CartItemController::class, "create");
$createCartItemRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createCartItemRoute);
$router->addRoute($createCartItemRoute);

$updateCartItemRoute = new Route(Urls::transformRouteUrl("/cart-items/:id"), HttpMethodsType::PUT, CartItemController::class, "update");
$updateCartItemRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateCartItemRoute);
$router->addRoute($updateCartItemRoute);

$deleteCartItemRoute = new Route(Urls::transformRouteUrl("/cart-items/:id"), HttpMethodsType::DELETE, CartItemController::class, "delete");
$deleteCartItemRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteCartItemRoute);
$router->addRoute($deleteCartItemRoute);
