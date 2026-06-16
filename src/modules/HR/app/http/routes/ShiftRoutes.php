<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\ShiftController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleWare = new GuardMiddleware();
$gateMiddleWare = new GateMiddleware([RoleTypes::HR , RoleTypes::MANAGER]);

$getShiftsRoute = new Route(Urls::transformRouteUrl("/shifts/") , HttpMethodsType::GET , ShiftController::class , 'index');
$getShiftsRoute->addMiddleware($guardMiddleWare)->addMiddleware($gateMiddleWare);
$router->addRoute($getShiftsRoute);
$middlewareRoutes->addRoute($getShiftsRoute);

$getShiftRoute = new Route(Urls::transformRouteUrl("/shifts/:id") , HttpMethodsType::GET , ShiftController::class , 'get');
$getShiftRoute->addMiddleware($guardMiddleWare)->addMiddleware($gateMiddleWare);
$router->addRoute($getShiftRoute);
$middlewareRoutes->addRoute($getShiftRoute);

$createShiftRoute = new Route(Urls::transformRouteUrl("/shifts/create") , HttpMethodsType::POST , ShiftController::class , 'create');
$createShiftRoute->addMiddleware($guardMiddleWare)->addMiddleware($gateMiddleWare);
$router->addRoute($createShiftRoute);
$middlewareRoutes->addRoute($createShiftRoute);

$updateShiftRoute = new Route(Urls::transformRouteUrl("/shifts/:id") , HttpMethodsType::PUT , ShiftController::class , 'update');
$updateShiftRoute->addMiddleware($guardMiddleWare)->addMiddleware($gateMiddleWare);
$router->addRoute($updateShiftRoute);
$middlewareRoutes->addRoute($updateShiftRoute);

$deleteShiftRoute = new Route(Urls::transformRouteUrl("/shifts/:id") , HttpMethodsType::DELETE , ShiftController::class , "delete");
$deleteShiftRoute->addMiddleware($guardMiddleWare)->addMiddleware($gateMiddleWare);
$router->addRoute($deleteShiftRoute);
$middlewareRoutes->addRoute($deleteShiftRoute);
