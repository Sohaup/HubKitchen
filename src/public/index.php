<?php

use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\routes\Router;
use PostApi\shared\app\controllers\api\TestController;
use PostApi\shared\app\controllers\api\UserController;
use PostApi\shared\app\http\middlewares\LoggerMiddleware;
use PostApi\shared\app\http\proxies\ProxyMiddleware;
use PostApi\shared\app\http\proxies\ProxyMiddlewareForRoute;
use PostApi\shared\app\http\responses\errors\problem\ProblemJson;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\app\http\responses\success\serin\actions\Action;
use PostApi\shared\app\http\responses\success\serin\actions\Actions;
use PostApi\shared\app\http\responses\success\serin\actions\fields\Field;
use PostApi\shared\app\http\responses\success\serin\actions\fields\Fields;
use PostApi\shared\app\http\responses\success\serin\enities\Entities;
use PostApi\shared\app\http\responses\success\serin\enities\Entity;
use PostApi\shared\app\http\responses\success\serin\links\Link;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;
require_once __DIR__ . "/../../vendor/autoload.php";

$request = new Request();
$router = new Router();

$route2 = new Route(Urls::transformRouteUrl("/test/get") , HttpMethodsType::PUT ,  TestController::class , 'get');
$router->addRoute($route2);
$route1 = new Route(Urls::transformRouteUrl("/users/get") , HttpMethodsType::POST , UserController::class , 'create');
$router->addRoute($route1);
$proxy = new ProxyMiddlewareForRoute($route2->path);
$proxy->addMiddleware(new LoggerMiddleware());
$proxy->execute($request);
$router->resolve($request->path , $request->method);