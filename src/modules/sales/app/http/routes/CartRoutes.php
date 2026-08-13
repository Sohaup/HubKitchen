<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\sales\app\controllers\CartController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::SALES, RoleTypes::MANAGER, RoleTypes::USER]);

$getCartRoute = new Route(Urls::transformRouteUrl("/carts/:id"), HttpMethodsType::GET, CartController::class, "get");
$getCartRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCartRoute);
$router->addRoute($getCartRoute);

$getCartsRoute = new Route(Urls::transformRouteUrl("/carts/"), HttpMethodsType::GET, CartController::class, "index");
$getCartsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCartsRoute);
$router->addRoute($getCartsRoute);

$createCartRoute = new Route(Urls::transformRouteUrl("/carts/create"), HttpMethodsType::POST, CartController::class, "create");
$createCartRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createCartRoute);
$router->addRoute($createCartRoute);

$updateCartRoute = new Route(Urls::transformRouteUrl("/carts/:id"), HttpMethodsType::PUT, CartController::class, "update");
$updateCartRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateCartRoute);
$router->addRoute($updateCartRoute);

$deleteCartRoute = new Route(Urls::transformRouteUrl("/carts/:id"), HttpMethodsType::DELETE, CartController::class, "delete");
$deleteCartRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteCartRoute);
$router->addRoute($deleteCartRoute);
