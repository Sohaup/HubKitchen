<?php

namespace PostApi\modules\CS\app\controllers;

use Error;
use PostApi\modules\CS\domain\services\ticket\CreateTicketAction;
use PostApi\modules\CS\domain\services\ticket\DeleteTicketAction;
use PostApi\modules\CS\domain\services\ticket\GetTicketCollectionAction;
use PostApi\modules\CS\domain\services\ticket\GetTicketItemAction;
use PostApi\modules\CS\domain\services\ticket\UpdateTicketAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class TicketController
{
    public function index()
    {
        try {
            $serin = GetTicketCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['type'])) {
            return ViewError::viewProplem('create ticket error', 'missing required paramters', 1, 'missing required paramter type', 400);
        }
        try {
            $item = CreateTicketAction::execute();
            $serin = GetTicketItemAction::execute($item->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetTicketItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no ticket for this id", 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['type'])) {
            return ViewError::viewProplem('update ticket error', 'missing required paramters', 1, 'missing required paramter type', 400);
        }
        try {
            UpdateTicketAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'updated']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no ticket for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteTicketAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no ticket for this id", 400);
        }
    }
}
