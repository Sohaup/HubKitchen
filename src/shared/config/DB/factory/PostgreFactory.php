<?php
namespace PostApi\shared\config\DB\factory;

use Override;
use PostApi\shared\config\DB\DB;
use PostApi\shared\config\DB\Postgre;

class PostgreFactory extends MainFactory
{
    private DB $dataBase;
    #[Override]
    public function CreateConnection()
    {
        $this->dataBase = new Postgre();
        return $this->dataBase;
    }
}
