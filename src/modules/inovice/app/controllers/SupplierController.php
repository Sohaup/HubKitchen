<?php

namespace PostApi\modules\inovice\app\controllers;

use Error;
use Exception;
use PostApi\modules\inovice\domain\services\supplier\CreateSupplierAction;
use PostApi\modules\inovice\domain\services\supplier\DeleteSupplierAction;
use PostApi\modules\inovice\domain\services\supplier\GetSupplierCollectionAction;
use PostApi\modules\inovice\domain\services\supplier\GetSupplierItemAction;
use PostApi\modules\inovice\domain\services\supplier\UpdateSupplierAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class SupplierController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $supplierRepository = new \PostApi\modules\inovice\app\DB\repositories\SupplierRepository();
            $critiria = [];

            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['name'])) {
                $critiria['name'] = $body['name'];
            }

            if (!empty($critiria)) {
                $suppliers = $supplierRepository->findBy($critiria);
            } else {
                $suppliers = $supplierRepository->findAll();
            }

            $serin = GetSupplierCollectionAction::execute($suppliers);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {        
        $body = $request->body;
        if (!isset($body['name'])) {
            return ViewError::viewProplem('create supplier error', 'missing required paramters', 1, 'missing required paramters name', 400);
        }
        try {
            $supplier = CreateSupplierAction::execute($body);
            $serin = GetSupplierItemAction::execute($supplier->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetSupplierItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no supplier for this id", 400);
        }
    }

    public function update(Request $request,string $id)
    {        
        $body = $request->body;
        if (!isset($body['name'])) {
            return ViewError::viewProplem('update supplier error', 'missing required paramters', 1, 'missing required paramters name', 400);
        }
        try {
            UpdateSupplierAction::execute($id , $body);
            http_response_code(200);
            return Json::toJson(['message' => 'supplier updated successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no supplier for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteSupplierAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'supplier deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no supplier for this id", 500);
        }
    }
}
