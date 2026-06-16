<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use PostApi\modules\HR\domain\services\applicationCycle\CreateApplicationCycleAction;
use PostApi\modules\HR\domain\services\applicationCycle\DeleteApplicationCycleAction;
use PostApi\modules\HR\domain\services\applicationCycle\GetApplicationCycleCollectionAction;
use PostApi\modules\HR\domain\services\applicationCycle\GetApplicationCycleItemAction;
use PostApi\modules\HR\domain\services\applicationCycle\UpdateApplicationCycleAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ApplicationCycleController implements ApiControllerContract
{
    public function index()
    {
        $serin = GetApplicationCycleCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    public function get(string $id)
    {
        try {
            $serin = GetApplicationCycleItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display application cycle error", title: "incorrect parameter", status: true, detail: "no cycle for this id", statusCode: 400);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['name'] , $body['starts_at'] , $body['ends_at'] , $body['status'])) {
            return ViewError::viewProplem("creating application cycle error", "missing required paramters error", 1, "missing required paramters name , starts_at , ends_at , status", 400);
        }

        try {
            $entity = CreateApplicationCycleAction::execute();
            $serin = GetApplicationCycleItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create application cycle error", title: "incorrect parameter", status: true, detail: "unexpected error creating cycle", statusCode: 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['name'] , $body['starts_at'] , $body['ends_at'] , $body['status'])) {
            return ViewError::viewProplem("creating application cycle error", "missing required paramters error", 1, "missing required paramters name , starts_at , ends_at , status", 400);
        }
        try {
            UpdateApplicationCycleAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'update application cycle successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update application cycle error", title: "incorrect parameter", status: true, detail: "there is no corresponding cycle for this id", statusCode: 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteApplicationCycleAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'delete application cycle successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete application cycle error", title: "incorrect parameter", status: true, detail: "there is no corresponding cycle for this id", statusCode: 400);
        }
    }
}
