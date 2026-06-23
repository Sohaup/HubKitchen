<?php

namespace PostApi\modules\CS\app\controllers;

use Error;
use Exception;
use PostApi\modules\CS\domain\services\customerLog\CreateCustomerLogAction;
use PostApi\modules\CS\domain\services\customerLog\GetCustomerLogCollectionAction;
use PostApi\modules\CS\domain\services\customerLog\GetCustomerLogItemAction;
use PostApi\modules\CS\domain\services\customerLog\UpdateCustomerLogAction;
use PostApi\modules\CS\domain\services\customerLog\DeleteCustomerLogAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class CustomerLogController
{
    public function index()
    {
        try {
            $serin = GetCustomerLogCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create()
    {
        try {
            $request = new Request();
            $body = $request->body;
            if (!isset($body['customer_id'], $body['log_type'])) {
                return ViewError::viewProplem('create customer log error', 'missing required paramters', 1, 'missing required paramters customer_id , log_type', 400);
            }
            $createCustomerLogAction = new CreateCustomerLogAction();
            $item = $createCustomerLogAction->execute();
            $serin = GetCustomerLogItemAction::execute($item->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetCustomerLogItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no customer log for this id", 500);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['customer_id'], $body['log_type'])) {
            return ViewError::viewProplem('create customer log error', 'missing required paramters', 1, 'missing required paramters customer_id , log_type', 400);
        }
        try {
            UpdateCustomerLogAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'updated']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no customer log for this id", 500);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteCustomerLogAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no customer log for this id", 500);
        }
    }
}
