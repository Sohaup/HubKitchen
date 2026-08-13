<?php
namespace PostApi\shared\config\DB\factory;

use PostApi\shared\config\DB\DB;
use Override;
use PostApi\shared\config\DB\MySql;

class MySqlFactory extends MainFactory {
    private DB $dataBase;
    #[Override]
    public function CreateConnection()
    {
        $this->dataBase = new MySql();   
        return $this->dataBase;    
    }
}