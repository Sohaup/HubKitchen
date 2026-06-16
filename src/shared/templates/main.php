<?php

use PostApi\shared\config\DB\Conf;
use PostApi\shared\config\DB\DBCommand;
use PostApi\shared\config\Env;
use PostApi\shared\helpers\queryBuilder\builder\QueryBuilder;

Env::configureEnv();
$postgre = new DBCommand("pgsql" , $_ENV['PORT'] ,$_ENV['HOST'] , $_ENV['USER'] , $_ENV['PASSWORD'] , $_ENV['DBNAME']);
$dataBase = Conf::connect($postgre);
$queryBuilder = new QueryBuilder($postgre->pdo);