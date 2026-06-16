<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use Override;
use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\modules\HR\domain\entities\Department;
use PostApi\modules\HR\domain\services\departments\CreateDepartmentAction;
use PostApi\modules\HR\domain\services\departments\DeleteDepartmentAction;
use PostApi\modules\HR\domain\services\departments\GetDepartmentCollectionAction;
use PostApi\modules\HR\domain\services\departments\UpdateDepartmentAction;
use PostApi\modules\HR\domain\services\departments\GetDepartmentItemAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class DepartmentController implements ApiControllerContract
{
    #[Override]
    public function index()
    {
       $serin = GetDepartmentCollectionAction::execute();
       return Chache::checkCache($serin);
    }
    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetDepartmentItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {            
            return ViewError::viewProplem(type: "display department error ", title: "incorrect paramter", status: true, detail: "there is no corresponding departmet for this id", statusCode: 400);
        }
    }
    #[Override]
    public function create()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['name'])) {
            return ViewError::viewProplem("creating departments error", "missing required paramters error", 1, "missing required paramters name", 400);
        }
        $department = new Department();
        $department->setName($params['name']);
        $serin = CreateDepartmentAction::execute($department);
        http_response_code(201);
        return Json::toJson($serin);
    }
    #[Override]
    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['name'])) {
            return ViewError::viewProplem("updating departments error", "missing required paramters error", 1, "missing required paramters name", 400);
        }
        try {
            $departmentRepository = new DepartmentRepository();
            $department = $departmentRepository->findOne($id);
            $department->setName($params['name']);
            UpdateDepartmentAction::execute($department);
            http_response_code(200);
            return Json::toJson(["message"=> "update department successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update department error ", title: "incorrect paramter", status: true, detail: "there is no corresponding departmet for this id", statusCode: 400);
        }
    }
    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteDepartmentAction::execute($id);
            http_response_code(200);
            return Json::toJson(["message"=> "delete department successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete department error ", title: "incorrect paramter", status: true, detail: "there is no corresponding departmet for this id", statusCode: 400);
        }
    }
}
