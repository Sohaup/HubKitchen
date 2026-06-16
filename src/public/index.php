<?php

use PostApi\shared\app\http\proxies\ProxyMiddlewareForRoute;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../shared/templates/main.php";
require_once __DIR__ . "/../shared/templates/routes.php";
require_once __DIR__ . "/../modules/auth/app/http/routes/authRoutes.php";
require_once __DIR__ . "/../modules/HR/app/http/routes/HrRoutes.php";

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

$proxyMiddleware = new ProxyMiddlewareForRoute($middlewareRoutes);
$proxyMiddleware->execute($request);
$router->resolve($request->path, $request->method);
