<?php

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../shared/templates/main.php";
require_once __DIR__ . "/../modules/auth/app/http/routes/authRoutes.php";


// var_dump($router->routes);

$router->resolve($request->path , $request->method);
