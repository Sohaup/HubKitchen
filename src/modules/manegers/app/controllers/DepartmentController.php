<?php

namespace PostApi\modules\manegers\app\controllers;

use Error;
use Exception;

use PostApi\modules\manegers\domain\services\department\CreateDepartmentAction;
use PostApi\modules\manegers\domain\services\department\UpdateDepartmentAction;
use PostApi\modules\manegers\domain\services\department\DeleteDepartmentAction;
use PostApi\modules\manegers\domain\services\department\GetDepartmentCollectionAction;
use PostApi\modules\manegers\domain\services\department\GetDepartmentItemAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class DepartmentController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $serin = GetDepartmentCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {       
        $body = $request->body;
        if (!isset($body['name'])) {
            return ViewError::viewProplem('create department error', 'missing required paramters', 1, 'missing required paramters name', 400);
        }
        try {
            $department = CreateDepartmentAction::execute($body);
            $serin = GetDepartmentItemAction::execute($department->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetDepartmentItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no department for this id", 400);
        }
    }

    public function update(Request $request,string $id)
    {        
        $body = $request->body;
        if (!isset($body['name'])) {
            return ViewError::viewProplem('update department error', 'missing required paramters', 1, 'missing required paramters name', 400);
        }
        try {
            UpdateDepartmentAction::execute($id , $body);
            http_response_code(200);
            return Json::toJson(['message' => 'department updated successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1,"no department for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteDepartmentAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'department deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1,"no department for this id", 400);
        }
    }
}
