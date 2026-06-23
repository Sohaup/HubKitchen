<?php

namespace PostApi\modules\CS\app\controllers;

use Error;
use PostApi\modules\CS\domain\services\caseinteract\CreateCaseInterActionAction;
use PostApi\modules\CS\domain\services\caseinteract\DeleteCaseInterActionAction;
use PostApi\modules\CS\domain\services\caseinteract\GetCaseInterActionCollectionAction;
use PostApi\modules\CS\domain\services\caseinteract\GetCaseInterActionItemAction;
use PostApi\modules\CS\domain\services\caseinteract\UpdateCaseInterActionAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class CaseInterActionController
{
    public function index()
    {
        try {
            $serin = GetCaseInterActionCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['customer_id'], $body['employee_id'], $body['action_id'], $body['status_id'], $body['action'] , $body['ticket_id'] )) {
            return ViewError::viewProplem('create case interaction error', 'missing required paramters', 1, 'missing required paramters', 400);
        }
        try {
            $item = CreateCaseInterActionAction::execute();            
            $serin = GetCaseInterActionItemAction::execute($item->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetCaseInterActionItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no case interaction for this id", 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['customer_id'], $body['employee_id'], $body['action_id'], $body['status_id'], $body['action'] , $body['ticket_id'])) {
            return ViewError::viewProplem('update case interaction error', 'missing required paramters', 1, 'missing required paramters', 400);
        }
        try {
            UpdateCaseInterActionAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'updated']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no case interaction for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteCaseInterActionAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no case interaction for this id", 400);
        }
    }
}
