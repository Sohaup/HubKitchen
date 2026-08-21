<?php

namespace PostApi\modules\CS\app\controllers;

use Error;
use PostApi\modules\CS\domain\services\caseinteract\CreateCaseInterActionAction;
use PostApi\modules\CS\domain\services\caseinteract\DeleteCaseInterActionAction;
use PostApi\modules\CS\domain\services\caseinteract\GetCaseInterActionCollectionAction;
use PostApi\modules\CS\domain\services\caseinteract\GetCaseInterActionItemAction;
use PostApi\modules\CS\domain\services\caseinteract\UpdateCaseInterActionAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class CaseInterActionController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $criteria = [];
            if (isset($body['id'])) {
                $criteria['id'] = $body['id'];
            }
            if (isset($body['customer_id'])) {
                $criteria['customer_id'] = $body['customer_id'];
            }
            if (isset($body['employee_id'])) {
                $criteria['employee_id'] = $body['employee_id'];
            }
            if (isset($body['action_id'])) {
                $criteria['action_id'] = $body['action_id'];
            }
            if (isset($body['status_id'])) {
                $criteria['status_id'] = $body['status_id'];
            }
            if (isset($body['ticket_id'])) {
                $criteria['ticket_id'] = $body['ticket_id'];
            }
            if (isset($body['action'])) {
                $criteria['action'] = $body['action'];
            }
            if (isset($body['interacted_at'])) {
                $criteria['interacted_at'] = $body['interacted_at'];
            }

            if (!empty($criteria)) {
                $repository = new \PostApi\modules\CS\app\DB\repositories\CaseInterActionRepository();
                $items = $repository->findBy($criteria);
                $serin = GetCaseInterActionCollectionAction::execute($items);
            } else {
                $serin = GetCaseInterActionCollectionAction::execute();
            }
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {      
        $body = $request->body;
        if (!isset($body['customer_id'], $body['employee_id'], $body['action_id'], $body['status_id'], $body['action'] , $body['ticket_id'] )) {
            return ViewError::viewProplem('create case interaction error', 'missing required paramters', 1, 'missing required paramters', 400);
        }
        try {
            $item = CreateCaseInterActionAction::execute($body);            
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

    public function update(Request $request,string $id)
    {        
        $body = $request->body;
        if (!isset($body['customer_id'], $body['employee_id'], $body['action_id'], $body['status_id'], $body['action'] , $body['ticket_id'])) {
            return ViewError::viewProplem('update case interaction error', 'missing required paramters', 1, 'missing required paramters', 400);
        }
        try {
            UpdateCaseInterActionAction::execute($id , $body);
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
