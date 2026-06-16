<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use PostApi\modules\HR\domain\services\saleryComponent\CreateSaleryComponentAction;
use PostApi\modules\HR\domain\services\saleryComponent\DeleteSaleryComponentAction;
use PostApi\modules\HR\domain\services\saleryComponent\GetSaleryComponentCollectionAction;
use PostApi\modules\HR\domain\services\saleryComponent\GetSaleryComponentItemAction;
use PostApi\modules\HR\domain\services\saleryComponent\UpdateSaleryComponentAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class SaleryComponentController implements ApiControllerContract
{
    public function index()
    {
        $serin = GetSaleryComponentCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    public function get(string $id)
    {
        try {
            $serin = GetSaleryComponentItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display salery component error", title: "incorrect parameter", status: true, detail: "no component for this id", statusCode: 400);
        }
    }

    public function create()
    {
        $request = new Request();   
        $body = $request->body;
        if (!isset($body['name'], $body['type'], $body['calc_type'] )) {
            return ViewError::viewProplem("creating salery component error", "missing required paramters error", 1, "missing required paramters name , type , calc_type  ", 400);
        }
        try {            
            $entity = CreateSaleryComponentAction::execute();
            $serin = GetSaleryComponentItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create salery component error", title: "incorrect parameter", status: true, detail: "unexpected error creating component", statusCode: 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();   
        $body = $request->body;
        if (!isset($body['name'], $body['type'], $body['calc_type'])) {
            return ViewError::viewProplem("updating salery component error", "missing required paramters error", 1, "missing required paramters name , type , calc_type ", 400);
        }
        try {
            UpdateSaleryComponentAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'update salery component successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update salery component error", title: "incorrect parameter", status: true, detail: "there is no corresponding component for this id", statusCode: 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteSaleryComponentAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'delete salery component successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete salery component error", title: "incorrect parameter", status: true, detail: "there is no corresponding component for this id", statusCode: 400);
        }
    }
}


