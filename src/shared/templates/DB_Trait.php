<?php

namespace PostApi\shared\templates;

// require_once __DIR__ . "/main.php";

use PDO;
use PostApi\shared\config\DB\DBManeger;
use PostApi\shared\config\Env;
use PostApi\shared\helpers\queryBuilder\builder\QueryBuilder;

trait DB_Trait
{    
    public PDO $dataBase;
    public QueryBuilder $queryBuilder;
    
    public function initialize()
    {
        Env::configureEnv();        
        $this->dataBase = DBManeger::connect();
        $this->queryBuilder = new QueryBuilder($this->dataBase);
    }

    public function getDbInstance() {
        return $this->dataBase;
    }
}
