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
    public function index(Request $request)
    {
        try {
            $repository = new \PostApi\modules\HR\app\DB\repositories\EmployeeRepository();
            $body = $request->body;
            $critiria = [];
            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['employee_status'])) {
                $critiria['employee_status'] = $body['employee_status'];
            }
            if (isset($body['martial_status'])) {
                $critiria['martial_status'] = $body['martial_status'];
            }
            if (isset($body['user_id'])) {
                $critiria['user_id'] = $body['user_id'];
            }
            if (isset($body['job_id'])) {
                $critiria['job_id'] = $body['job_id'];
            }
            if (isset($body['manager_id'])) {
                $critiria['manager_id'] = $body['manager_id'];
            }
            if (isset($body['department_id'])) {
                $critiria['department_id'] = $body['department_id'];
            }
            if (isset($body['addresse_id'])) {
                $critiria['addresse_id'] = $body['addresse_id'];
            }
            if (!empty($critiria)) {
                $collection = $repository->findBy($critiria);
                $serin = GetEmployeeCollectionAction::execute($collection);
            } else {
                $collection = $repository->findAll();
                $serin = GetEmployeeCollectionAction::execute($collection);
            }
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (\Throwable $err) {
            return ViewError::viewProplem("display employee error", "paramter error", 1, "internal server error", 500);
        }
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
    public function create(Request $request)
    {       
        $params = $request->body;
        if (!isset($params['employeeStatus'], $params['martialStatus'], $params['user_id'], $params['job_id'], $params['manager_id'], $params['department_id'], $params['addresse_id'])) {
            return ViewError::viewProplem("creating employee error", "missing required paramters error", 1, "missing required paramters employeeStatus , martialStatus , user_id , job_id , manager_id , employeedAt , department_id , addresse_id", 400);
        }
        try {
            $createEmployeeAction = new CreateEmployeeAction();
            $entity = $createEmployeeAction->execute($params);
            $serin = GetEmployeeItemAction::execute($entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create employee error ", title: "incorrect paramter", status: true, detail: "unexpected error creating employee", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request,string $id)
    {
        try {
            UpdateEmployeeAction::execute($id , $request->body);
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
