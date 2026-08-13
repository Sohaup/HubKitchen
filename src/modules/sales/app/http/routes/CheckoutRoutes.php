<?php
use PostApi\modules\sales\app\controllers\CheckoutRedirectController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

// $guardMiddleware = new GuardMiddleware();

$sucessCheckoutRoute = new Route(Urls::transformRouteUrl("/checkouts/success") , HttpMethodsType::GET , CheckoutRedirectController::class , "success");
// $sucessCheckoutRoute->addMiddleware($guardMiddleware);
$router->addRoute($sucessCheckoutRoute);
// $middlewareRoutes->addRoute($sucessCheckoutRoute);

$failedCheckouteRoute = new Route(Urls::transformRouteUrl("/checkouts/failed") , HttpMethodsType::GET , CheckoutRedirectController::class , "failed");
// $failedCheckouteRoute->addMiddleware($guardMiddleware);
$router->addRoute($failedCheckouteRoute);
// $middlewareRoutes->addRoute($failedCheckouteRoute);