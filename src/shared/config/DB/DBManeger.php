<?php

namespace PostApi\shared\config\DB;

use Error;
use PDO;
use PostApi\shared\config\DB\factory\MySqlFactory;
use PostApi\shared\config\DB\factory\PostgreFactory;
use PostApi\shared\config\Env;

class DBManeger
{
    private static ?PDO $instance = null;
    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}
    public static function connect() : PDO
    {
        if (!self::$instance) {
            Env::configureEnv();
            $dataBase = match ($_ENV['DRIVER']) {
                'pgsql' => new PostgreFactory(),
                'mysql' => new MySqlFactory(),
                default => throw new Error("unsopported dataBase Driver")
            };            
            self::$instance = $dataBase->getConnection()->getPdo();
        }
        return self::$instance;
    }
}
