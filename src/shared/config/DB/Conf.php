<?php
namespace PostApi\shared\config\DB;

use PDO;

interface Connect
{
    public static function connect(DB $db);
}

class Conf implements Connect
{
    private static ?PDO $instance = null;
    private function __construct() {}
    public static function connect(DB $db)
    {
        if (!self::$instance) {
            self::$instance = $db->pdo;
        }
        return self::$instance;
    }
}
