<?php

use PostApi\modules\auth\app\http\middlewares\GateMiddleware;
use PostApi\modules\auth\app\http\middlewares\GuardMiddleware;
use PostApi\modules\auth\helpers\types\RoleTypes;
use PostApi\modules\HR\app\controllers\SkillController;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../../../../shared/templates/routes.php";

$guardMiddleware = new GuardMiddleware();
$gateMiddleware = new GateMiddleware([RoleTypes::HR , RoleTypes::MANAGER , RoleTypes::USER]);

$getSkillRoute = new Route(Urls::transformRouteUrl("/skills/:id") , HttpMethodsType::GET , SkillController::class , 'get');
$getSkillRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($getSkillRoute);
$middlewareRoutes->addRoute($getSkillRoute);

$getSkillsRoute = new Route(Urls::transformRouteUrl("/skills/") , HttpMethodsType::GET , SkillController::class , 'index');
$getSkillsRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($getSkillsRoute);
$middlewareRoutes->addRoute($getSkillsRoute);

$createSkillRoute = new Route(Urls::transformRouteUrl("/skills/create") , HttpMethodsType::POST , SkillController::class , 'create');
$createSkillRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($createSkillRoute);
$middlewareRoutes->addRoute($createSkillRoute);

$updateSkillRoute = new Route(Urls::transformRouteUrl("/skills/:id") , HttpMethodsType::PUT , SkillController::class , 'update');
$updateSkillRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($updateSkillRoute);
$middlewareRoutes->addRoute($updateSkillRoute);

$deleteSkillRoute = new Route(Urls::transformRouteUrl("/skills/:id") , HttpMethodsType::DELETE , SkillController::class , 'delete');
$deleteSkillRoute->addMiddleware($guardMiddleware)->addMiddleware($gateMiddleware);
$router->addRoute($deleteSkillRoute);
$middlewareRoutes->addRoute($deleteSkillRoute);