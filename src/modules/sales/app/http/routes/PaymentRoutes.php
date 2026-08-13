<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\sales\app\controllers\PaymentController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guradMiddleWare = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::MANAGER , RoleTypes::USER ]);

$getPaymentRoute = new Route(Urls::transformRouteUrl("/payments/:id") , HttpMethodsType::GET , PaymentController::class , "get");
$getPaymentRoute->addMiddleware($guradMiddleWare)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getPaymentRoute);
$router->addRoute($getPaymentRoute);

$getPaymentsRoute = new Route(Urls::transformRouteUrl("/payments/") , HttpMethodsType::GET , PaymentController::class , "index");
$getPaymentsRoute->addMiddleware($guradMiddleWare)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getPaymentsRoute);
$router->addRoute($getPaymentsRoute);

$createPaymentRoute = new Route(Urls::transformRouteUrl("/payments/create") , HttpMethodsType::POST , PaymentController::class , "create");
$createPaymentRoute->addMiddleware($guradMiddleWare)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createPaymentRoute);
$router->addRoute($createPaymentRoute);

$updatePaymentRoute = new Route(Urls::transformRouteUrl("/payments/:id"), HttpMethodsType::PUT , PaymentController::class , "update");
$updatePaymentRoute->addMiddleware($guradMiddleWare)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updatePaymentRoute);
$router->addRoute($updatePaymentRoute);

$deletePaymentRoute = new Route(Urls::transformRouteUrl("/payments/:id") , HttpMethodsType::DELETE , PaymentController::class , "delete");
$deletePaymentRoute->addMiddleware($guradMiddleWare)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updatePaymentRoute);
$router->addRoute($updatePaymentRoute);
