<?php
namespace PostApi\shared\config\DB\factory;

use PostApi\shared\config\DB\DB;

abstract class MainFactory {
    abstract public function CreateConnection();

    public function getConnection() : DB {
        $connection = $this->CreateConnection();
        return $connection;
    }
    
}