<?php

namespace PostApi\modules\HR\domain\services\saleryComponent;

use Override;
use PostApi\modules\HR\app\DB\repositories\SaleryComponentRepository;
use PostApi\modules\HR\domain\entities\SaleryComponent;
use PostApi\modules\HR\domain\EntityListeners\CreatePayRollJournalListener;
use PostApi\shared\app\http\requests\Request;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class CreateSaleryComponentAction 
{
   
    public static function execute()
    {
        $request = new Request();
        $body = $request->body;
        $name = $body['name'];       
        $type = $body['type'];
        $calc = $body['calc_type'];
        $entity = new SaleryComponent(null, $name, $type, $calc);
        $repo = new SaleryComponentRepository();
        $repo->create($entity);     
        return $entity;
    }
        
}
