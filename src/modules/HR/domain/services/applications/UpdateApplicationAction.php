<?php

namespace PostApi\modules\HR\domain\services\applications;

use PostApi\modules\HR\app\DB\repositories\ApplicationRepository;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\fecade\Files;

class UpdateApplicationAction
{
    public static function execute(int $id)
    {
        $request = new Request();
        $params = $request->body;
        $applicationRepository = new ApplicationRepository();
        $application = $applicationRepository->findOne($id);       
        if ($application) {
            if (isset($params['name'])) {               
                $application->setName($params['name']);
            }
            if (isset($params['email'])) {
                $application->setEmail($params['email']);
            }
            if (isset($params['phone'])) {
                $application->setPhone($params['phone']);
            }
            if (isset($request->files['cv'])) {
                $cvPath = Files::storeFile('cv'); 
                $application->setCv($cvPath);
            }            
            $applicationRepository->update($application);
        }
    }
}
