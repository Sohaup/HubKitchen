<?php

namespace PostApi\modules\HR\domain\services\applications;

use PostApi\modules\HR\app\DB\repositories\ApplicationRepository;
use PostApi\modules\HR\domain\entities\Application;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\fecade\Files;

class CreateApplicationAction
{
    public static function execute()
    {
        $request = new Request();
        $params = $request->body;

        $applicationRepository = new ApplicationRepository();
        $application = new Application();
        $application->setName($params['name']);
        $application->setEmail($params['email']);
        $application->setPhone($params['phone']);        
        $cvPath = Files::storeFile('cv'); 
        $application->setCv($cvPath);     
           
        $applicationRepository->create($application);
        return $application;
    }
}
