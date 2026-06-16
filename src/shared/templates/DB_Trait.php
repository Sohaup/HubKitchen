<?php

namespace PostApi\shared\templates;

require_once __DIR__ . "/main.php";

use PDO;
use PostApi\shared\config\DB\Conf;
use PostApi\shared\config\DB\DBCommand;
use PostApi\shared\config\Env;
use PostApi\shared\helpers\queryBuilder\builder\QueryBuilder;

trait DB_Trait
{
    public DBCommand $postgre;
    public PDO $dataBase;
    public QueryBuilder $queryBuilder;
    
    public function initialize()
    {
        Env::configureEnv();
        $this->postgre = new DBCommand("pgsql", $_ENV['PORT'], $_ENV['HOST'], $_ENV['USER'], $_ENV['PASSWORD'], $_ENV['DBNAME']);
        $this->dataBase = Conf::connect($this->postgre);
        $this->queryBuilder = new QueryBuilder($this->postgre->pdo);
    }
    public function getDbInstance() {
        return $this->postgre;
    }
}
