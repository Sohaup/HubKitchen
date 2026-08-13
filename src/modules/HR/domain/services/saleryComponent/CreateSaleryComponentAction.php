<?php

namespace PostApi\modules\HR\domain\services\saleryComponent;


use PostApi\modules\HR\app\DB\repositories\SaleryComponentRepository;
use PostApi\modules\HR\domain\entities\SaleryComponent;

class CreateSaleryComponentAction 
{   
    public static function execute(array $body)
    {        
        $name = $body['name'];       
        $type = $body['type'];
        $calc = $body['calc_type'];
        $entity = new SaleryComponent(null, $name, $type, $calc);
        $repo = new SaleryComponentRepository();
        $repo->create($entity);     
        return $entity;
    }        
}
