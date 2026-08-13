<?php

namespace PostApi\shared\config\DB;

use Override;
use PDO;
use PostApi\shared\config\Env;

class MySql implements DB
{
  private PDO $pdo;
  #[Override]
  public function __construct()
  {
    Env::configureEnv();
    $this->pdo = new PDO("mysql:host={$_ENV['HOST']};dbname={$_ENV['DBNAME']}", $$_ENV['MYSQLUSER'], $_ENV['MYSQLPASSWORD']);
  }

  #[Override]
  public function getPdo()
  {
    return $this->pdo;
  }
}
