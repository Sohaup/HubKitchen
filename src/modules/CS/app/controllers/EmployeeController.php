<?php

namespace PostApi\modules\CS\app\controllers;

use Error;
use Exception;
use PostApi\modules\CS\domain\services\employee\CreateEmployeeAction;
use PostApi\modules\CS\domain\services\employee\GetEmployeeCollectionAction;
use PostApi\modules\CS\domain\services\employee\GetEmployeeItemAction;
use PostApi\modules\CS\domain\services\employee\UpdateEmployeeAction;
use PostApi\modules\CS\domain\services\employee\DeleteEmployeeAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class EmployeeController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $criteria = [];
            if (isset($body['id'])) {
                $criteria['id'] = $body['id'];
            }
            if (isset($body['user_id'])) {
                $criteria['user_id'] = $body['user_id'];
            }
            if (isset($body['employee_id'])) {
                $criteria['employee_id'] = $body['employee_id'];
            }
            if (isset($body['role_id'])) {
                $criteria['role_id'] = $body['role_id'];
            }

            if (!empty($criteria)) {
                $repository = new \PostApi\modules\CS\app\DB\repositories\EmployeeRepository();
                $items = $repository->findBy($criteria);
                $serin = GetEmployeeCollectionAction::execute($items);
            } else {
                $serin = GetEmployeeCollectionAction::execute();
            }
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {      
        $body = $request->body;
        if (!isset($body['user_id'], $body['employee_id'], $body['role_id'])) {
            return ViewError::viewProplem('create employee error', 'missing required paramters', 1, 'missing required paramters employee_id , user_id , role_id', 400);
        }
        try {
            $item = CreateEmployeeAction::execute($body);            
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

    public function update(Request $request,string $id)
    {       
        $body = $request->body;
        if (!isset($body['user_id'], $body['employee_id'], $body['role_id'])) {
            return ViewError::viewProplem('create employee error', 'missing required paramters', 1, 'missing required paramters employee_id , user_id , role_id', 400);
        }
        try {
            UpdateEmployeeAction::execute($id , $body);
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
