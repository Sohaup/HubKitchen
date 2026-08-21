<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\sales\app\controllers\OfferController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::SALES, RoleTypes::MANAGER, RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(100, 60);

$getOfferRoute = new Route(Urls::transformRouteUrl("/offers/:id"), HttpMethodsType::GET, OfferController::class, "get");
$getOfferRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getOfferRoute);
$router->addRoute($getOfferRoute);

$getOffersRoute = new Route(Urls::transformRouteUrl("/offers/"), HttpMethodsType::GET, OfferController::class, "index");
$getOffersRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getOffersRoute);
$router->addRoute($getOffersRoute);

$createOfferRoute = new Route(Urls::transformRouteUrl("/offers/create"), HttpMethodsType::POST, OfferController::class, "create");
$createOfferRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createOfferRoute);
$router->addRoute($createOfferRoute);

$updateOfferRoute = new Route(Urls::transformRouteUrl("/offers/:id"), HttpMethodsType::PUT, OfferController::class, "update");
$updateOfferRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateOfferRoute);
$router->addRoute($updateOfferRoute);

$deleteOfferRoute = new Route(Urls::transformRouteUrl("/offers/:id"), HttpMethodsType::DELETE, OfferController::class, "delete");
$deleteOfferRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteOfferRoute);
$router->addRoute($deleteOfferRoute);
