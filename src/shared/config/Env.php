<?php
namespace PostApi\shared\config;

use Dotenv\Dotenv;

class Env
{
    private function __construct()
    {
        
    }
    public static function configureEnv()
    {
        $dotEnv = Dotenv::createImmutable(__DIR__ ."/../../../");
        $dotEnv->load();
    }
}
