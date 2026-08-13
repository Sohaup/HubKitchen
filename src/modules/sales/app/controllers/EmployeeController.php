<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\EmployeeRepository;
use PostApi\modules\sales\domain\entities\Employee;
use PostApi\modules\sales\domain\services\employee\CreateEmployeeAction;
use PostApi\modules\sales\domain\services\employee\DeleteEmployeeAction;
use PostApi\modules\sales\domain\services\employee\GetEmployeeCollectionAction;
use PostApi\modules\sales\domain\services\employee\GetEmployeeItemAction;
use PostApi\modules\sales\domain\services\employee\UpdateEmployeeAction;
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
    public function create(Request $request)
    {        
        $params = $request->body;
        if (!isset($params['user_id'], $params['country'])) {
            return ViewError::viewProplem("creating employee error", "missing required paramters error", 1, "missing required paramters user_id, country", 400);
        }
        try {
            $employee = new Employee();         
            $employee->setUserId($params['user_id']);
            $employee->setCountry($params['country']);

            $serin = CreateEmployeeAction::execute($employee);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create employee error ", title: "incorrect paramter", status: true, detail: "unexpected error creating employee", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request,string $id)
    {        
        $params = $request->body;
        if (!isset($params['user_id'], $params['country'])) {
            return ViewError::viewProplem("updating employee error", "missing required paramters error", 1, "missing required paramters user_id, country", 400);
        }
        try {
            $employeeRepository = new EmployeeRepository();
            $employee = $employeeRepository->findOne($id);
            if (!$employee) {
                return ViewError::viewProplem(type: "update employee error ", title: "incorrect paramter", status: true, detail: "employee not found", statusCode: 400);
            }
            $employee->setUserId($params['user_id']);
            $employee->setCountry($params['country']);

            UpdateEmployeeAction::execute($employee);
            http_response_code(200);
            return Json::toJson(["message" => "update employee successfuly"]);
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
            return Json::toJson(["message" => "delete employee successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete employee error ", title: "incorrect paramter", status: true, detail: "there is no corresponding employee for this id", statusCode: 400);
        }
    }
}
