<?php

namespace PostApi\modules\inovice\app\controllers;

use Error;
use Exception;
use PostApi\modules\inovice\domain\services\product\CreateProductAction;
use PostApi\modules\inovice\domain\services\product\DeleteProductAction;
use PostApi\modules\inovice\domain\services\product\GetProductCollectionAction;
use PostApi\modules\inovice\domain\services\product\GetProductItemAction;
use PostApi\modules\inovice\domain\services\product\UpdateProductAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ProductController
{
    public function index()
    {
        try {
            $serin = GetProductCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 400);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['name'], $body['price'], $body['quantity'], $body['supplier_id'])) {
            return ViewError::viewProplem('create product error', 'missing required paramters', 1, 'missing required paramters name , price , quantity , supplier_id', 400);
        }
        try {
            $product = CreateProductAction::execute();
            $serin = GetProductItemAction::execute($product->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetProductItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no supplier for this id", 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['name'], $body['price'], $body['quantity'], $body['supplier_id'])) {
            return ViewError::viewProplem('update product error', 'missing required paramters', 1, 'missing required paramters name , price , quantity , supplier_id', 400);
        }
        try {
            UpdateProductAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'product updated successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no supplier for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteProductAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'product deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no supplier for this id" , 400);
        }
    }
}
