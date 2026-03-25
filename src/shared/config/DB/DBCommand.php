<?php
namespace PostApi\shared\config\DB;


use Exception;
use PDO;


abstract class DB
{
    public PDO $pdo;
    public function __construct(string $port, string $host, string $user, string $password, string $dbName) {}   
}

class DBCommand extends DB {
    public function __construct(string $type,string $port, string $host, string $user, string $password, string $dbName) {
        switch($type) {
            case "mysql":
                $mysql = new MySql($port , $host , $user , $password , $dbName);
                $this->pdo = $mysql->pdo;
            break;
            case "pgsql":
                $pgsql = new PostgreSql($port , $host , $user , $password , $dbName);
                $this->pdo = $pgsql->pdo;
            break;
            default:
            throw new Exception("unvalid database type");       
        }
    } 
}

class PostgreSql extends DB
{
    public function __construct($port, string $host, string $user, string $password, string $dbName)
    {
        $this->pdo = new PDO("pgsql:host=$host;dbname=$dbName", $user, $password);
    }
}

class MySql extends DB
{
    public function __construct($port, string $host, string $user, string $password, string $dbName)
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $password);
    }
}
