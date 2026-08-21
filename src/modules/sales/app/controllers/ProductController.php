<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\CategoryRepository;
use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\domain\entities\Product;
use PostApi\modules\sales\domain\services\product\CreateProductAction;
use PostApi\modules\sales\domain\services\product\DeleteProductAction;
use PostApi\modules\sales\domain\services\product\GetProductCollectionAction;
use PostApi\modules\sales\domain\services\product\GetProductItemAction;
use PostApi\modules\sales\domain\services\product\UpdateProductAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ProductController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $productRepository = new ProductRepository();
            $critiria = [];

            if (isset($body['name'])) {
                $critiria['name'] = $body['name'];
            }
            if (isset($body['category_id'])) {
                $critiria['category_id'] = $body['category_id'];
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

            if (isset($body['stripe_id'])) {
                $critiria['stripe_id'] = $body['stripe_id'];
            }
                       
            if (!empty($critiria)) {
                $products = $productRepository->findBy($critiria);
            } else {
                $products = $productRepository->findAll();
            }

            $serin = GetProductCollectionAction::execute($products);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display product error ", title: "incorrect paramter", status: true, detail: "internal server error", statusCode: 500);
        }
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetProductItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display product error ", title: "incorrect paramter", status: true, detail: "there is no corresponding product for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {
        $params = $request->body;
        if (!isset($params['name'], $params['price'], $request->files['image'], $params['props'], $params['category_id'])) {
            return ViewError::viewProplem("creating product error", "missing required paramters error", 1, "missing required paramters name, price , image , props , category_id", 400);
        }
        try {
            $product = new Product();
            $product->setName($params['name']);
            $product->setPrice((float)$params['price']);
            $product->setProps($params['props']);
            $categoryRepo = new CategoryRepository();
            $catrgory = $categoryRepo->findOne((int)$params['category_id']);
            $product->setCategory($catrgory);
            $serin = CreateProductAction::execute($product);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create product error ", title: "incorrect paramter", status: true, detail: "unexpected error creating product", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request, string $id)
    {
        $params = $request->body;
        $updateFile = $request->files['image'] ?? false;
        try {
            $productRepository = new ProductRepository();
            $product = $productRepository->findOne($id);
            if (!$product) {
                return ViewError::viewProplem(type: "update product error ", title: "incorrect paramter", status: true, detail: "product not found", statusCode: 400);
            }
            UpdateProductAction::execute($product, (bool)$updateFile, $params);
            http_response_code(200);
            return Json::toJson(["message" => "update product successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update product error ", title: "incorrect paramter", status: true, detail: "there is no corresponding product for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteProductAction::execute($id);
            http_response_code(200);
            return Json::toJson(["message" => "delete product successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete product error ", title: "incorrect paramter", status: true, detail: "there is no corresponding product for this id", statusCode: 400);
        }
    }
}
