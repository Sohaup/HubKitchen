<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use PostApi\modules\HR\domain\services\salery\CreateSaleryAction;
use PostApi\modules\HR\domain\services\salery\DeleteSaleryAction;
use PostApi\modules\HR\domain\services\salery\GetSaleryCollectionAction;
use PostApi\modules\HR\domain\services\salery\GetSaleryItemAction;
use PostApi\modules\HR\domain\services\salery\UpdateSaleryAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class SaleryController implements ApiControllerContract
{
    public function index()
    {
        $serin = GetSaleryCollectionAction::execute();
        return  Chache::checkCache($serin);
    }

    public function get(string $id)
    {
        try {
            $serin = GetSaleryItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display salery error", title: "incorrect parameter", status: true, detail: "no salery for this id", statusCode: 400);
        }
    }

    public function create()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['employee_id'], $params['salery'])) {
            return ViewError::viewProplem("creating salery error", "missing required paramters error", 1, "missing required paramters employee_id , salery ", 400);
        }
        try {
            $entity = CreateSaleryAction::execute();
            $serin = GetSaleryItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create salery error", title: "incorrect parameter", status: true, detail: "unexpected error creating salery", statusCode: 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['employee_id'], $params['salery'])) {
            return ViewError::viewProplem("updating salery error", "missing required paramters error", 1, "missing required paramters employee_id , salery ", 400);
        }
        try {
            UpdateSaleryAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'update salery successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update salery error", title: "incorrect parameter", status: true, detail: "there is no corresponding salery for this id", statusCode: 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteSaleryAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'delete salery successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete salery error", title: "incorrect parameter", status: true, detail: "there is no corresponding salery for this id", statusCode: 400);
        }
    }
}
