<?php

namespace PostApi\modules\inovice\app\controllers;

use Error;
use Exception;
use PostApi\modules\inovice\domain\services\prucher\CreatePrucherAction;
use PostApi\modules\inovice\domain\services\prucher\DeletePrucherAction;
use PostApi\modules\inovice\domain\services\prucher\GetPrucherCollectionAction;
use PostApi\modules\inovice\domain\services\prucher\GetPrucherItemAction;
use PostApi\modules\inovice\domain\services\prucher\UpdatePrucherAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class PrucherController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $serin = GetPrucherCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {        
        $body = $request->body;
        if (!isset($body['quantity'], $body['product_id'])) {
            return ViewError::viewProplem('create prucher error', 'missing required paramters', 1, 'missing required paramters quantity, product_id', 400);
        }
        try {
            $prucher = CreatePrucherAction::execute($body);
            $serin = GetPrucherItemAction::execute($prucher->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetPrucherItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no prucher for this id", 400);
        }
    }

    public function update(Request $request,string $id)
    {      
        $body = $request->body;
        try {
            UpdatePrucherAction::execute($id , $body);
            http_response_code(200);
            return Json::toJson(['message' => 'prucher updated successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no prucher for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeletePrucherAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'prucher deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no prucher for this id", 400);
        }
    }
}
