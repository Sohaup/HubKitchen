<?php
use PostApi\modules\sales\app\controllers\CheckoutRedirectController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$throttleMiddleware = new ThrottleMiddleware(10, 60);
$sucessCheckoutRoute = new Route(Urls::transformRouteUrl("/checkouts/success") , HttpMethodsType::GET , CheckoutRedirectController::class , "success");
$sucessCheckoutRoute->addMiddleware($throttleMiddleware);
$router->addRoute($sucessCheckoutRoute);
$middlewareRoutes->addRoute($sucessCheckoutRoute);

$failedCheckouteRoute = new Route(Urls::transformRouteUrl("/checkouts/failed") , HttpMethodsType::GET , CheckoutRedirectController::class , "failed");
$failedCheckouteRoute->addMiddleware($throttleMiddleware);
$router->addRoute($failedCheckouteRoute);
$middlewareRoutes->addRoute($failedCheckouteRoute);