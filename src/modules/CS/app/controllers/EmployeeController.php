<?php

namespace PostApi\modules\CS\app\controllers;

use Error;
use Exception;
use PostApi\modules\CS\domain\services\employee\CreateEmployeeAction;
use PostApi\modules\CS\domain\services\employee\GetEmployeeCollectionAction;
use PostApi\modules\CS\domain\services\employee\GetEmployeeItemAction;
use PostApi\modules\CS\domain\services\employee\UpdateEmployeeAction;
use PostApi\modules\CS\domain\services\employee\DeleteEmployeeAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class EmployeeController
{
    public function index()
    {
        try {
            $serin = GetEmployeeCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['user_id'], $body['employee_id'], $body['role_id'])) {
            return ViewError::viewProplem('create employee error', 'missing required paramters', 1, 'missing required paramters employee_id , user_id , role_id', 400);
        }
        try {
            $item = CreateEmployeeAction::execute();            
            $serin = GetEmployeeItemAction::execute($item->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetEmployeeItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no customer for this id", 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['user_id'], $body['employee_id'], $body['role_id'])) {
            return ViewError::viewProplem('create employee error', 'missing required paramters', 1, 'missing required paramters employee_id , user_id , role_id', 400);
        }
        try {
            UpdateEmployeeAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'updated']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no employee for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteEmployeeAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no customer for this id", 400);
        }
    }
}
