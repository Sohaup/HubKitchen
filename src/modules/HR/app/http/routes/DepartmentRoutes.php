<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\DepartmentController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR , RoleTypes::MANAGER , RoleTypes::USER]);

$getDepartmentRoute = new Route(Urls::transformRouteUrl("/departments/:id") , HttpMethodsType::GET , DepartmentController::class , "get");
$getDepartmentRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($getDepartmentRoute);
$middlewareRoutes->addRoute($getDepartmentRoute);

$getDepartmentsRoute = new Route(Urls::transformRouteUrl("/departments/") , HttpMethodsType::GET , DepartmentController::class , "index");
$getDepartmentRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getDepartmentsRoute);
$router->addRoute($getDepartmentsRoute);

$createDepartmentRoute = new Route(Urls::transformRouteUrl("/departments/create") , HttpMethodsType::POST , DepartmentController::class , "create");
$createDepartmentRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createDepartmentRoute);
$router->addRoute($createDepartmentRoute);

$updateDepartmentRoute = new Route(Urls::transformRouteUrl("/departments/:id") , HttpMethodsType::PUT , DepartmentController::class , "update");
$updateDepartmentRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);    
$middlewareRoutes->addRoute($updateDepartmentRoute);
$router->addRoute($updateDepartmentRoute);

$deleteDepartmentRoute = new Route(Urls::transformRouteUrl("/departments/:id") , HttpMethodsType::DELETE , DepartmentController::class , "delete");
$deleteDepartmentRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);    
$middlewareRoutes->addRoute($deleteDepartmentRoute);
$router->addRoute($deleteDepartmentRoute);

