<?php

use PostApi\modules\CS\helpers\adapters\mailer\Mailer;
use PostApi\shared\app\http\proxies\ProxyMiddlewareForRoute;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../shared/templates/main.php";
require_once __DIR__ . "/../shared/templates/routes.php";
require_once __DIR__ . "/../modules/auth/app/http/routes/authRoutes.php";
require_once __DIR__ . "/../modules/HR/app/http/routes/HrRoutes.php";
require_once __DIR__ . "/../modules/CS/app/http/routes/CsRoutes.php";

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

// $mailer = new Mailer();
// $emailContents = file_get_contents(__DIR__ . "/../modules/CS/helpers/templates/emailTemplate.html");

// $mailer->Authorisaze("sohybta560@gmail.com" , "sohaib");
// $mailer->buildMail("Welcome sohaib"  , $emailContents , $emailContents);
// $mailer->send();

$proxyMiddleware = new ProxyMiddlewareForRoute($middlewareRoutes);
$proxyMiddleware->execute($request);

$router->resolve($request->path, $request->method);
