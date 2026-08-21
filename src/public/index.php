<?php

use PostApi\shared\app\controllers\api\MainController;
use PostApi\shared\app\http\proxies\ProxyMiddlewareForRoute;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../shared/templates/main.php";
require_once __DIR__ . "/../shared/templates/routes.php";
require_once __DIR__ . "/../modules/auth/app/http/routes/authRoutes.php";
require_once __DIR__ . "/../modules/HR/app/http/routes/HrRoutes.php";
require_once __DIR__ . "/../modules/CS/app/http/routes/CsRoutes.php";
require_once __DIR__ . "/../modules/inovice/app/http/routes/InoviceRoutes.php";
require_once __DIR__ . "/../modules/manegers/app/http/routes/manegersRoutes.php";
require_once __DIR__ . "/../modules/sales/app/http/routes/SalesRoutes.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
$mainRoute = new Route(Urls::transformRouteUrl("/") , HttpMethodsType::GET ,MainController::class , 'get' );
$router->addRoute($mainRoute);
$proxyMiddleware = new ProxyMiddlewareForRoute($middlewareRoutes);
$proxyMiddleware->execute($request);

$router->resolve($request->path, $request->method);
