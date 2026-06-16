<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use Override;
use PostApi\modules\HR\app\DB\repositories\ApplicationRepository;
use PostApi\modules\HR\domain\services\applications\CreateApplicationAction;
use PostApi\modules\HR\domain\services\applications\DeleteApplicationAction;
use PostApi\modules\HR\domain\services\applications\GetApplicationCollectionAction;
use PostApi\modules\HR\domain\services\applications\GetApplicationItemAction;
use PostApi\modules\HR\domain\services\applications\UpdateApplicationAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\ViewError;

class ApplicationController implements ApiControllerContract
{
    #[Override]
    public function index()
    {
        $serin = GetApplicationCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetApplicationItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display application error ", title: "incorrect paramter", status: true, detail: "there is no corresponding application for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create()
    {
        $request = new Request();
        $params = $request->body;
       
        if (!isset($params['name'], $params['email'], $params['phone'] , $request->files['cv']))  {
            return ViewError::viewProplem("creating application error", "missing required paramters error", 1, "missing required paramters name, email, phone, cv (body or file), ", 400);
        }        
        try {
            $application = CreateApplicationAction::execute();
            $serin = GetApplicationItemAction::execute((int)$application->getId());
            http_response_code(201);
            return Json::toJson($serin);            
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create application error ", title: "incorrect paramter", status: true, detail: "unexpected error creating application", statusCode: 400);
        }
    }

    #[Override]
    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['name'], $params['email'], $params['phone'] )) {
            return ViewError::viewProplem("updating application error", "missing required paramters error", 1, "missing required paramters name, email, phone", 400);
        }
        try {
            UpdateApplicationAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "update application successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update application error ", title: "incorrect paramter", status: true, detail: "there is no corresponding application for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteApplicationAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "delete application successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete application error ", title: "incorrect paramter", status: true, detail: "there is no corresponding application for this id", statusCode: 400);
        }
    }
}
