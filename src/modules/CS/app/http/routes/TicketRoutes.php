<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\CS\app\controllers\TicketController;
use PostApi\shared\app\http\middlewares\ThrottleMiddleware;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::CS , RoleTypes::MANAGER , RoleTypes::USER]);
$throttleMiddleware = new ThrottleMiddleware(20, 60);

$getTicketsRoute = new Route(Urls::transformRouteUrl("/tickets/"), HttpMethodsType::GET, TicketController::class, 'index');
$getTicketsRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getTicketsRoute);
$router->addRoute($getTicketsRoute);

$createTicketRoute = new Route(Urls::transformRouteUrl("/tickets/create"), HttpMethodsType::POST, TicketController::class, 'create');
$createTicketRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createTicketRoute);
$router->addRoute($createTicketRoute);

$getTicketRoute = new Route(Urls::transformRouteUrl("/tickets/:id"), HttpMethodsType::GET, TicketController::class, 'get');
$getTicketRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getTicketRoute);
$router->addRoute($getTicketRoute);

$updateTicketRoute = new Route(Urls::transformRouteUrl("/tickets/:id"), HttpMethodsType::PUT, TicketController::class, 'update');
$updateTicketRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($updateTicketRoute);
$router->addRoute($updateTicketRoute);

$deleteTicketRoute = new Route(Urls::transformRouteUrl("/tickets/:id"), HttpMethodsType::DELETE, TicketController::class, 'delete');
$deleteTicketRoute->addMiddleware($throttleMiddleware)->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($deleteTicketRoute);
$router->addRoute($deleteTicketRoute);
