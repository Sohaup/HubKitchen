<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\sales\app\controllers\ReviewController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::SALES, RoleTypes::MANAGER, RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(100, 60);

$getReviewRoute = new Route(Urls::transformRouteUrl("/reviews/:id"), HttpMethodsType::GET, ReviewController::class, "get");
$getReviewRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getReviewRoute);
$router->addRoute($getReviewRoute);

$getReviewsRoute = new Route(Urls::transformRouteUrl("/reviews/"), HttpMethodsType::GET, ReviewController::class, "index");
$getReviewsRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getReviewsRoute);
$router->addRoute($getReviewsRoute);

$createReviewRoute = new Route(Urls::transformRouteUrl("/reviews/create"), HttpMethodsType::POST, ReviewController::class, "create");
$createReviewRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createReviewRoute);
$router->addRoute($createReviewRoute);

$updateReviewRoute = new Route(Urls::transformRouteUrl("/reviews/:id"), HttpMethodsType::PUT, ReviewController::class, "update");
$updateReviewRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateReviewRoute);
$router->addRoute($updateReviewRoute);

$deleteReviewRoute = new Route(Urls::transformRouteUrl("/reviews/:id"), HttpMethodsType::DELETE, ReviewController::class, "delete");
$deleteReviewRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteReviewRoute);
$router->addRoute($deleteReviewRoute);
