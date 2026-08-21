<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use PostApi\modules\HR\domain\services\payroll\CreatePayrollAction;
use PostApi\modules\HR\domain\services\payroll\DeletePayrollAction;
use PostApi\modules\HR\domain\services\payroll\GetPayrollCollectionAction;
use PostApi\modules\HR\domain\services\payroll\GetPayrollItemAction;
use PostApi\modules\HR\domain\services\payroll\UpdatePayrollAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class PayrollController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $repository = new \PostApi\modules\HR\app\DB\repositories\PayrollRepository();
            $body = $request->body;
            $critiria = [];
            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['employee_id'])) {
                $critiria['employee_id'] = $body['employee_id'];
            }
            if (isset($body['amount'])) {
                $critiria['amount'] = $body['amount'];
            }
            if (isset($body['salery_component_id'])) {
                $critiria['salery_component_id'] = $body['salery_component_id'];
            }
            if (!empty($critiria)) {
                $collection = $repository->findBy($critiria);
                $serin = GetPayrollCollectionAction::execute($collection);
            } else {
                $collection = $repository->findAll();
                $serin = GetPayrollCollectionAction::execute($collection);
            }
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (\Throwable $err) {
            return ViewError::viewProplem("display payroll error", "paramter error", 1, "internal server error", 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetPayrollItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display payroll error", title: "incorrect parameter", status: true, detail: "no payroll for this id", statusCode: 400);
        }
    }

    public function create(Request $request)
    {         
        $params = $request->body;
        if (!isset($params['employee_id'], $params['amount'], $params['salery_component_id'])) {
            return ViewError::viewProplem("creating payroll error", "missing required paramters error", 1, "missing required paramters employee_id, amount , salery_component_id ", 400);
        }
        try {
            $createPayRollAction = new CreatePayrollAction(); 
            $entity = $createPayRollAction->execute($params);
            $serin = GetPayrollItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create payroll error", title: "incorrect parameter", status: true, detail: "unexpected error creating payroll", statusCode: 400);
        }
    }

    public function update(Request $request,string $id)
    {         
        $params = $request->body;        
        if (!isset($params['employee_id'], $params['amount'], $params['salery_component_id'])) {
            return ViewError::viewProplem("updating payroll error", "missing required paramters error", 1, "missing required paramters employee_id, amount , salery_component_id ", 400);
        }
        try {
            UpdatePayrollAction::execute((int)$id , $params);
            http_response_code(200);
            return Json::toJson(['message' => 'update payroll successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update payroll error", title: "incorrect parameter", status: true, detail: "there is no corresponding payroll for this id", statusCode: 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeletePayrollAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'delete payroll successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete payroll error", title: "incorrect parameter", status: true, detail: "there is no corresponding payroll for this id", statusCode: 400);
        }
    }
}
