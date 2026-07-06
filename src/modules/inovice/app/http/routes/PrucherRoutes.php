<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\inovice\app\controllers\PrucherController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::CS, RoleTypes::MANAGER, RoleTypes::USER]);

$getPruchersRoute = new Route(Urls::transformRouteUrl("/pruchers/"), HttpMethodsType::GET, PrucherController::class, 'index');
$getPruchersRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getPruchersRoute);
$router->addRoute($getPruchersRoute);

$createPrucherRoute = new Route(Urls::transformRouteUrl("/pruchers/create"), HttpMethodsType::POST, PrucherController::class, 'create');
$createPrucherRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createPrucherRoute);
$router->addRoute($createPrucherRoute);

$getPrucherRoute = new Route(Urls::transformRouteUrl("/pruchers/:id"), HttpMethodsType::GET, PrucherController::class, 'get');
$getPrucherRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getPrucherRoute);
$router->addRoute($getPrucherRoute);

$updatePrucherRoute = new Route(Urls::transformRouteUrl("/pruchers/:id"), HttpMethodsType::PUT, PrucherController::class, 'update');
$updatePrucherRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updatePrucherRoute);
$router->addRoute($updatePrucherRoute);

$deletePrucherRoute = new Route(Urls::transformRouteUrl("/pruchers/:id"), HttpMethodsType::DELETE, PrucherController::class, 'delete');
$deletePrucherRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deletePrucherRoute);
$router->addRoute($deletePrucherRoute);
