<?php

namespace PostApi\modules\CS\app\controllers;

use Error;
use Exception;
use PostApi\modules\CS\domain\services\role\CreateRoleAction;
use PostApi\modules\CS\domain\services\role\GetRoleCollectionAction;
use PostApi\modules\CS\domain\services\role\GetRoleItemAction;
use PostApi\modules\CS\domain\services\role\UpdateRoleAction;
use PostApi\modules\CS\domain\services\role\DeleteRoleAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class RoleController
{
    public function index()
    {
        try {
            $serin = GetRoleCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['name'])) {
            return ViewError::viewProplem('create role error', 'missing required paramters', 1, 'missing required paramters name', 400);
        }
        try {
            $item = CreateRoleAction::execute();
            $serin = GetRoleItemAction::execute($item->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetRoleItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no role for this id", 400);
        }
    }

    public function update(string $id)
    {
       $request = new Request();
        $body = $request->body;
        if (!isset($body['name'])) {
            return ViewError::viewProplem('create role error', 'missing required paramters', 1, 'missing required paramters name', 400);
        } 
        try {
            UpdateRoleAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'updated']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no role for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteRoleAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no role for this id", 400);
        }
    }
}
