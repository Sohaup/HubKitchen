<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use Override;
use PostApi\modules\HR\domain\services\employee\CreateEmployeeAction;
use PostApi\modules\HR\domain\services\employee\DeleteEmployeeAction;
use PostApi\modules\HR\domain\services\employee\GetEmployeeCollectionAction;
use PostApi\modules\HR\domain\services\employee\GetEmployeeItemAction;
use PostApi\modules\HR\domain\services\employee\UpdateEmployeeAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class EmployeeController implements ApiControllerContract
{
    #[Override]
    public function index()
    {
        $serin = GetEmployeeCollectionAction::execute();        
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetEmployeeItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display employee error ", title: "incorrect paramter", status: true, detail: "there is no corresponding employee for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['employeeStatus'], $params['martialStatus'], $params['user_id'], $params['job_id'], $params['manager_id'], $params['department_id'], $params['addresse_id'])) {
            return ViewError::viewProplem("creating employee error", "missing required paramters error", 1, "missing required paramters employeeStatus , martialStatus , user_id , job_id , manager_id , employeedAt , department_id , addresse_id", 400);

        }
        try {
            $createEmployeeAction = new CreateEmployeeAction();
            $entity = $createEmployeeAction->execute();
            $serin = GetEmployeeItemAction::execute($entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create employee error ", title: "incorrect paramter", status: true, detail: "unexpected error creating employee", statusCode: 400);
        }
    }

    #[Override]
    public function update(string $id)
    {
        try {
            UpdateEmployeeAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => "update employee successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update employee error ", title: "incorrect paramter", status: true, detail: "there is no corresponding employee for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteEmployeeAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => "delete employee successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete employee error ", title: "incorrect paramter", status: true, detail: "there is no corresponding employee for this id", statusCode: 400);
        }
    }
}
