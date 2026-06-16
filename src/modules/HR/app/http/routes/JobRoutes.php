<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\JobController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR , RoleTypes::MANAGER ,  RoleTypes::USER]);

$getJobRoute = new Route(Urls::transformRouteUrl("/jobs/:id") , HttpMethodsType::GET , JobController::class , "get");
$getJobRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getJobRoute);
$router->addRoute($getJobRoute);

$getJobsRoute = new Route(Urls::transformRouteUrl("/jobs/") , HttpMethodsType::GET , JobController::class , "index");
$getJobsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($getJobsRoute);
$router->addRoute($getJobsRoute);

$createJobRoute = new Route(Urls::transformRouteUrl("/jobs/create") , HttpMethodsType::POST , JobController::class , "create");
$createJobRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($createJobRoute);   
$router->addRoute($createJobRoute);

$updateJobRoute = new Route(Urls::transformRouteUrl("/jobs/:id") , HttpMethodsType::PUT , JobController::class , "update");
$updateJobRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);    
$middlewareRoutes->addRoute($updateJobRoute);
$router->addRoute($updateJobRoute);

$deleteJobRoute = new Route(Urls::transformRouteUrl("/jobs/:id") , HttpMethodsType::DELETE , JobController::class , "delete");
$deleteJobRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);    
$middlewareRoutes->addRoute($deleteJobRoute);
$router->addRoute($deleteJobRoute);

$assignApplicationRoute = new Route(Urls::transformRouteUrl("/jobs/addApplication"), HttpMethodsType::POST, JobController::class, "assignApplicationToJob");
$assignApplicationRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($assignApplicationRoute);
$router->addRoute($assignApplicationRoute);

$removeApplicationRoute = new Route(Urls::transformRouteUrl("/jobs/removeApplication"), HttpMethodsType::DELETE, JobController::class, "removeApplicationFromJob");
$removeApplicationRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$middlewareRoutes->addRoute($removeApplicationRoute);
$router->addRoute($removeApplicationRoute);