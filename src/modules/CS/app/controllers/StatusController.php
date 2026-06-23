<?php

namespace PostApi\modules\CS\app\controllers;

use Error;
use PostApi\modules\CS\domain\services\status\CreateStatusAction;
use PostApi\modules\CS\domain\services\status\DeleteStatusAction;
use PostApi\modules\CS\domain\services\status\GetStatusCollectionAction;
use PostApi\modules\CS\domain\services\status\GetStatusItemAction;
use PostApi\modules\CS\domain\services\status\UpdateStatusAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class StatusController
{
    public function index()
    {
        try {
            $serin = GetStatusCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['status'])) {
            return ViewError::viewProplem('create status error', 'missing required paramters', 1, 'missing required paramter status or issued_at', 400);
        }
        try {
            $item = CreateStatusAction::execute();
            $serin = GetStatusItemAction::execute($item->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetStatusItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no status for this id", 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['status'])) {
            return ViewError::viewProplem('update status error', 'missing required paramters', 1, 'missing required paramter status ', 400);
        }
        try {
            UpdateStatusAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'updated']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no status for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteStatusAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no status for this id", 400);
        }
    }
}
