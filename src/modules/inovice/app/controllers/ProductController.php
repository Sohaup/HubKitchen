<?php

namespace PostApi\modules\inovice\app\controllers;

use Error;
use Exception;
use PostApi\modules\inovice\domain\services\product\CreateProductAction;
use PostApi\modules\inovice\domain\services\product\DeleteProductAction;
use PostApi\modules\inovice\domain\services\product\GetProductCollectionAction;
use PostApi\modules\inovice\domain\services\product\GetProductItemAction;
use PostApi\modules\inovice\domain\services\product\UpdateProductAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ProductController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $productRepository = new \PostApi\modules\inovice\app\DB\repositories\ProductRepository();
            $critiria = [];

            if (isset($body['supplier_id'])) {
                $critiria['supplier_id'] = $body['supplier_id'];
            }
            if (isset($body['name'])) {
                $critiria['name'] = $body['name'];
            }

            if (isset($body['price'])) {
                $critiria['price'] = $body['price'];
            } elseif (isset($body['greater_than_price'])) {
                $critiria['greater_than_price'] = $body['greater_than_price'];
            } elseif (isset($body['less_than_price'])) {;
                $critiria['less_than_price'] = $body['less_than_price'];
            } elseif (isset($body['greater_than_or_equal_price'])) {
                $critiria['greater_than_or_equal_price'] = $body['greater_than_or_equal_price'];
            } elseif (isset($body['less_than_or_equal_price'])) {
                $critiria['less_than_or_equal_price'] = $body['less_than_or_equal_price'];
            }

            if (isset($body['quantity'])) {
                $critiria['quantity'] = $body['quantity'];
            } elseif (isset($body['greater_than_quantity'])) {
                $critiria['greater_than_quantity'] = $body['greater_than_quantity'];
            } elseif (isset($body['less_than_quantity'])) {;
                $critiria['less_than_quantity'] = $body['less_than_quantity'];
            } elseif (isset($body['greater_than_or_equal_quantity'])) {
                $critiria['greater_than_or_equal_quantity'] = $body['greater_than_or_equal_quantity'];
            } elseif (isset($body['less_than_or_equal_quantity'])) {
                $critiria['less_than_or_equal_quantity'] = $body['less_than_or_equal_quantity'];
            }

            if (!empty($critiria)) {
                $products = $productRepository->findBy($critiria);
            } else {
                $products = $productRepository->findAll();
            }

            $serin = GetProductCollectionAction::execute($products);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 400);
        }
    }

    public function create(Request $request)
    {
        $body = $request->body;
        if (!isset($body['name'], $body['price'], $body['quantity'], $body['supplier_id'], $request->files['image'])) {
            return ViewError::viewProplem('create product error', 'missing required paramters', 1, 'missing required paramters name , price , quantity , supplier_id , image', 400);
        }
        try {
            $createProductAction = new CreateProductAction();
            $product = $createProductAction->execute($body);
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

    public function update(Request $request, string $id)
    {
        $body = $request->body;
        if (isset($request->files['image'])) {
            $body['image'] = $request->files['image'];
        }

        try {
            UpdateProductAction::execute($id, $body);
            http_response_code(200);
            return Json::toJson(['message' => 'product updated successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no supplier for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            $deleteProductAction = new DeleteProductAction();
            $deleteProductAction->execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'product deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no supplier for this id", 400);
        }
    }
}
