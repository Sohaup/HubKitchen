<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\sales\app\controllers\CategoryController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::SALES, RoleTypes::MANAGER , RoleTypes::USER]);

$getCategoryRoute = new Route(Urls::transformRouteUrl("/categories/:id"), HttpMethodsType::GET, CategoryController::class, "get");
$getCategoryRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCategoryRoute);
$router->addRoute($getCategoryRoute);

$getCategoriesRoute = new Route(Urls::transformRouteUrl("/categories/"), HttpMethodsType::GET, CategoryController::class, "index");
$getCategoriesRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getCategoriesRoute);
$router->addRoute($getCategoriesRoute);

$createCategoryRoute = new Route(Urls::transformRouteUrl("/categories/create"), HttpMethodsType::POST, CategoryController::class, "create");
$createCategoryRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createCategoryRoute);
$router->addRoute($createCategoryRoute);

$updateCategoryRoute = new Route(Urls::transformRouteUrl("/categories/:id"), HttpMethodsType::POST, CategoryController::class, "update");
$updateCategoryRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateCategoryRoute);
$router->addRoute($updateCategoryRoute);

$deleteCategoryRoute = new Route(Urls::transformRouteUrl("/categories/:id"), HttpMethodsType::DELETE, CategoryController::class, "delete");
$deleteCategoryRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteCategoryRoute);
$router->addRoute($deleteCategoryRoute);
