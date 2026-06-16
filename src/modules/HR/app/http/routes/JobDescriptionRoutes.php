<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\JobDescriptionController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleWare = new GateMiddleware([RoleTypes::HR , RoleTypes::MANAGER , RoleTypes::USER ]);

$getJobDescriptionRoute = new Route(Urls::transformRouteUrl("/jds/:id") , HttpMethodsType::GET , JobDescriptionController::class , 'get');
$getJobDescriptionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($getJobDescriptionRoute);
$middlewareRoutes->addRoute($getJobDescriptionRoute);

$getJobDescriptionsRoute = new Route(Urls::transformRouteUrl("/jds/") , HttpMethodsType::GET , JobDescriptionController::class , 'index');
$getJobDescriptionsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($getJobDescriptionsRoute);
$middlewareRoutes->addRoute($getJobDescriptionsRoute);

$createJobDescriptionRoute = new Route(Urls::transformRouteUrl("/jds/create") , HttpMethodsType::POST , JobDescriptionController::class , 'create');
$createJobDescriptionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($createJobDescriptionRoute);
$middlewareRoutes->addRoute($createJobDescriptionRoute);

$updateJobDescriptionRoute = new Route(Urls::transformRouteUrl("/jds/:id") , HttpMethodsType::PUT , JobDescriptionController::class , 'update');
$updateJobDescriptionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($updateJobDescriptionRoute);
$middlewareRoutes->addRoute($updateJobDescriptionRoute);

$deleteJobDescriptionRoute = new Route(Urls::transformRouteUrl("/jds/:id") , HttpMethodsType::DELETE , JobDescriptionController::class , 'delete');
$deleteJobDescriptionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($deleteJobDescriptionRoute);
$middlewareRoutes->addRoute($deleteJobDescriptionRoute);

$assignSkillToJobDescriptionRoute = new Route(Urls::transformRouteUrl("/jds/addSkill") , HttpMethodsType::POST , JobDescriptionController::class , 'assignSkillToJob');
$assignSkillToJobDescriptionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($assignSkillToJobDescriptionRoute);
$middlewareRoutes->addRoute($assignSkillToJobDescriptionRoute);

$removeSkillFromJobDescriptionRoute = new Route(Urls::transformRouteUrl("/jds/removeSkill") , HttpMethodsType::DELETE , JobDescriptionController::class , 'removeSkillFromJob');
$removeSkillFromJobDescriptionRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleWare);
$router->addRoute($removeSkillFromJobDescriptionRoute);
$middlewareRoutes->addRoute($removeSkillFromJobDescriptionRoute);