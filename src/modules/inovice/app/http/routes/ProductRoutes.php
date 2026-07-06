<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\inovice\app\controllers\ProductController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::CS, RoleTypes::MANAGER, RoleTypes::USER]);

$getProductsRoute = new Route(Urls::transformRouteUrl("/products/"), HttpMethodsType::GET, ProductController::class, 'index');
$getProductsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getProductsRoute);
$router->addRoute($getProductsRoute);

$createProductRoute = new Route(Urls::transformRouteUrl("/products/create"), HttpMethodsType::POST, ProductController::class, 'create');
$createProductRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createProductRoute);
$router->addRoute($createProductRoute);

$getProductRoute = new Route(Urls::transformRouteUrl("/products/:id"), HttpMethodsType::GET, ProductController::class, 'get');
$getProductRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getProductRoute);
$router->addRoute($getProductRoute);

$updateProductRoute = new Route(Urls::transformRouteUrl("/products/:id"), HttpMethodsType::PUT, ProductController::class, 'update');
$updateProductRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateProductRoute);
$router->addRoute($updateProductRoute);

$deleteProductRoute = new Route(Urls::transformRouteUrl("/products/:id"), HttpMethodsType::DELETE, ProductController::class, 'delete');
$deleteProductRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteProductRoute);
$router->addRoute($deleteProductRoute);
