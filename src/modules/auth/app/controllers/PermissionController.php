<?php

namespace PostApi\modules\auth\app\controllers;

use PostApi\modules\auth\app\DB\repositories\PermissionRepository;
use PostApi\modules\auth\domain\services\permissions\CreatePermissionAction;
use PostApi\modules\auth\domain\services\permissions\GetPermissionItemAction;
use PostApi\modules\auth\domain\services\permissions\GetPermissionsCollectionAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;
use Throwable;

class PermissionController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $permissionRepository = new PermissionRepository();
            $body = $request->body;
            $critiria = [];
            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['name'])) {
                $critiria['name'] = $body['name'];
            }
            if (!empty($critiria)) {
                $permissions = $permissionRepository->findBy($critiria);
                $serin = GetPermissionsCollectionAction::execute($permissions);
            } else {
                $permissions = $permissionRepository->findAll();
                $serin = GetPermissionsCollectionAction::execute($permissions);
            }

            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Throwable $err) {
            return ViewError::viewProplem("display permission error", "paramter error", 1, "internal server error", 500);
        }
    }
    public function get(string $id)
    {
        try {
            $permissionRepository = new PermissionRepository();
            $permission  = $permissionRepository->findOne($id);
            $serin = GetPermissionItemAction::execute($permission);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Throwable $error) {
            return ViewError::viewProplem("display permission error", "paramter error", 1, "internal server error", 500);
        }
    }
    public function create(Request $request)
    {
        try {
            header("Content-Type:application/json");
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
        } catch (Throwable $err) {
            return ViewError::viewProplem("display permission error", "paramter error", 1, "internal server error", 500);
        }
    }
    public function update(Request $request, string $id)
    {
        header("Content-Type:application/json");
        $permissionRepository = new PermissionRepository();
        $params = $request->body;
        try {
            if (!isset($params['name'])) {
                return ViewError::viewProplem("updating permission error", "missing required paramters error", 1, "missing required paramter name", 400);
            }
            $permission = $permissionRepository->findOne($id);
            $permission->setName($params['name']);
            $permissionRepository->update($permission);
            http_response_code(200);
            return Json::toJson(['message' => "updateed permission successfuly"]);
        } catch (Throwable $error) {
            return ViewError::viewProplem("update permission error", "paramter error", 1, "internal server error", 500);
        }
    }
    public function delete(string $id)
    {
        try {
            $permissionRepository = new PermissionRepository();
            $permission = $permissionRepository->findOne($id);
            $permissionRepository->delete($id);
            http_response_code(200);
            return Json::toJson(['message' => "deleted permission successfuly"]);
        } catch (Throwable $error) {
            return ViewError::viewProplem("delete permission error", "paramter error", 1, "there is no corosponding permission for this id", 400);
        }
    }
}
