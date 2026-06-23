<?php

namespace PostApi\modules\CS\app\controllers;

use Error;
use PostApi\modules\CS\domain\services\action\CreateActionAction;
use PostApi\modules\CS\domain\services\action\DeleteActionAction;
use PostApi\modules\CS\domain\services\action\GetActionCollectionAction;
use PostApi\modules\CS\domain\services\action\GetActionItemAction;
use PostApi\modules\CS\domain\services\action\UpdateActionAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ActionController
{
    public function index()
    {
        try {
            $serin = GetActionCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['action'])) {
            return ViewError::viewProplem('create action error', 'missing required paramters', 1, 'missing required paramter action ', 400);
        }
        try {
            $item = CreateActionAction::execute();
            $serin = GetActionItemAction::execute($item->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetActionItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no action for this id", 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['action'])) {
            return ViewError::viewProplem('update action error', 'missing required paramters', 1, 'missing required paramter action ', 400);
        }
        try {
            UpdateActionAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'updated']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no action for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteActionAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no action for this id", 400);
        }
    }
}
