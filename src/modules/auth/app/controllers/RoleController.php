<?php

namespace PostApi\modules\auth\app\controllers;

use PostApi\modules\auth\app\DB\repositories\RoleRepository;
use PostApi\modules\auth\domain\services\Roles\CreateRoleAction;
use PostApi\modules\auth\domain\services\Roles\GetRoleCollectionAction;
use PostApi\modules\auth\domain\services\Roles\GetRoleItemAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;
use Throwable;

class RoleController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $roleRepository = new RoleRepository();
            $body = $request->body;
            $critiria = [];
            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['name'])) {
                $critiria['name'] = $body['name'];
            }
            if (!empty($critiria)) {
                $roles = $roleRepository->findBy($critiria);
                $serin = GetRoleCollectionAction::execute($roles);
            } else {
                $roles = $roleRepository->findAll();
                $serin = GetRoleCollectionAction::execute($roles);
            }
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Throwable $err) {
            return ViewError::viewProplem("display role error", "paramter error", 1, "internal server error", 500);
        }
    }
    public function get(string $id)
    {
        try {
            $roleRepository = new RoleRepository();
            $role  = $roleRepository->findOne($id);
            $serin = GetRoleItemAction::execute($role);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Throwable $error) {
            return ViewError::viewProplem("display role error", "paramter error", 1, "internal server error", 500);
        }
    }
    public function create(Request $request)
    {
        try {
            header("Content-Type:application/json");
            $params = $request->body;
            if (!isset($params['name'])) {
                return  ViewError::viewProplem("creating role error", "missing required paramters error", 1, "missing required paramter name ", 400);
            }
            $roleRepository = new RoleRepository();
            $role = CreateRoleAction::execute($params['name']);
            $roleRepository->create($role);
            $serin = GetRoleItemAction::execute($role);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Throwable $err) {
            return  ViewError::viewProplem("creating role error", "missing required paramters error", 1, "internal server error", 500);
        }
    }
    public function update(Request $request, string $id)
    {
        header("Content-Type:application/json");
        $roleRepository = new RoleRepository();
        $params = $request->body;
        try {
            if (!isset($params['name'])) {
                return ViewError::viewProplem("updating permission error", "missing required paramters error", 1, "missing required paramter name", 400);
            }
            $role = $roleRepository->findOne($id);
            $role->setName($params['name']);
            $roleRepository->update($role);
            http_response_code(200);
            return Json::toJson(['message' => "updateed role successfuly"]);
        } catch (Throwable $error) {
            return ViewError::viewProplem("update role error", "paramter error", 1, "internal server error", 500);
        }
    }
    public function delete(string $id)
    {
        try {
            $roleRepository = new RoleRepository();
            $role = $roleRepository->findOne($id);
            $roleRepository->delete($id);
            http_response_code(200);
            return Json::toJson(['message' => "deleted role successfuly"]);
        } catch (Throwable $error) {
            return ViewError::viewProplem("delete role error", "paramter error", 1, "internal server error", 500);
        }
    }
}
