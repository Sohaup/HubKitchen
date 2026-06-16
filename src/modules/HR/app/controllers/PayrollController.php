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
    public function index()
    {
        $serin = GetPayrollCollectionAction::execute();
        return Chache::checkCache($serin);
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

    public function create()
    {
        $request = new Request();   
        $params = $request->body;
        if (!isset($params['employee_id'], $params['amount'], $params['salery_component_id'])) {
            return ViewError::viewProplem("creating payroll error", "missing required paramters error", 1, "missing required paramters employee_id, amount , salery_component_id ", 400);
        }
        try {
            $createPayRollAction = new CreatePayrollAction(); 
            $entity = $createPayRollAction->execute();
            $serin = GetPayrollItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create payroll error", title: "incorrect parameter", status: true, detail: "unexpected error creating payroll", statusCode: 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();   
        $params = $request->body;        
        if (!isset($params['employee_id'], $params['amount'], $params['salery_component_id'])) {
            return ViewError::viewProplem("updating payroll error", "missing required paramters error", 1, "missing required paramters employee_id, amount , salery_component_id ", 400);
        }
        try {
            UpdatePayrollAction::execute((int)$id);
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
