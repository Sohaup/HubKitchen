<?php

namespace PostApi\modules\auth\app\controllers;

use Error;
use Exception;
use PostApi\modules\auth\app\DB\repositories\PermissionRepository;
use PostApi\modules\auth\domain\services\permissions\CreatePermissionAction;
use PostApi\modules\auth\domain\services\permissions\GetPermissionItemAction;
use PostApi\modules\auth\domain\services\permissions\GetPermissionsCollectionAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\ViewError;

class PermissionController implements ApiControllerContract
{
    public function index()
    {
        $permissionRepository = new PermissionRepository();
        $permissions = $permissionRepository->findAll();
        $serin = GetPermissionsCollectionAction::execute($permissions);
        http_response_code(200);
        return Json::toJson($serin);
    }
    public function get(string $id)
    {
        try {
            $permissionRepository = new PermissionRepository();
            $permission  = $permissionRepository->findOne($id);
            $serin = GetPermissionItemAction::execute($permission);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem("display permission error", "paramter error", 1, "there is no corosponding permission for this id", 400);
        }
    }
    public function create()
    {
        header("Content-Type:application/json");
        $request = new Request();
        $params = $request->body;
        if (!isset($params['name'])) {
            return  ViewError::viewProplem("creating permission error", "missing required paramters error", 1, "missing required paramter name ", 400);
        }
        $permissionRepository = new PermissionRepository();
        $permission = CreatePermissionAction::execute($params['name']);
        $permissionRepository->create($permission);
        $serin = GetPermissionItemAction::execute($permission);
        http_response_code(201);
        return Json::toJson($serin);
    }
    public function update(string $id)
    {
        header("Content-Type:application/json");
        $permissionRepository = new PermissionRepository();
        $request = new Request();
        $params = $request->body;
        try {
            if (!isset($params['name'])) {
                return ViewError::viewProplem("updating permission error", "missing required paramters error", 1, "missing required paramter name", 400);
            }
            $permission = $permissionRepository->findOne($id);
            $permission->setName($params['name']);
            $permissionRepository->update($permission);
            http_response_code(200);
            return Json::toJson(['message'=>"updateed permission successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem("update permission error", "paramter error", 1, "there is no corosponding permission for this id", 400);
        }
    }
    public function delete(string $id)
    {
        try {
            $permissionRepository = new PermissionRepository();
            $permission = $permissionRepository->findOne($id);
            $permissionRepository->delete($id);
            http_response_code(200);
            return Json::toJson(['message'=>"deleted permission successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem("delete permission error", "paramter error", 1, "there is no corosponding permission for this id", 400);
        }
    }
}
