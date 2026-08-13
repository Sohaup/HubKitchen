<?php

namespace PostApi\shared\config\DB;

use Override;
use PDO;
use PostApi\shared\config\Env;

class Postgre implements DB
{
    private PDO $pdo;
    public function __construct()
    {
        Env::configureEnv();
        $this->pdo = new PDO("pgsql:host={$_ENV['HOST']};dbname={$_ENV['DBNAME']}", $_ENV['USER'], $_ENV['PASSWORD']);
    }
    #[Override]
    public function getPdo()
    {
        return $this->pdo;
    }
}
