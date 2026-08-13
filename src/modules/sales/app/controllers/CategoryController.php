<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\CategoryRepository;
use PostApi\modules\sales\domain\entities\Category;
use PostApi\modules\sales\domain\services\category\CreateCategoryAction;
use PostApi\modules\sales\domain\services\category\DeleteCategoryAction;
use PostApi\modules\sales\domain\services\category\GetCategoryCollectionAction;
use PostApi\modules\sales\domain\services\category\GetCategoryItemAction;
use PostApi\modules\sales\domain\services\category\UpdateCategoryAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class CategoryController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        $serin = GetCategoryCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetCategoryItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display category error ", title: "incorrect parameter", status: true, detail: "there is no corresponding category for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {       
        $params = $request->body;
        if (!isset($params['name'] , $request->files['image'])) {
            return ViewError::viewProplem("creating category error", "missing required parameter error", 1, "missing required parameter name , image", 400);
        }
        try {
            $category = new Category();
            $category->setName($params['name']);
            $serin = CreateCategoryAction::execute($category);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create category error ", title: "incorrect parameter", status: true, detail: "unexpected error creating category", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request, string $id)
    {        
        $params = $request->body;
        $isFile = false;
        if (isset($request->files['image'])) {
            $isFile = true;    
        }
        try {
            $categoryRepository = new CategoryRepository();
            $category = $categoryRepository->findOne((int)$id);
            if (!$category) {
                return ViewError::viewProplem(type: "update category error ", title: "incorrect parameter", status: true, detail: "category not found", statusCode: 400);
            }         
            UpdateCategoryAction::execute($category, $params , $isFile);
            http_response_code(200);
            return Json::toJson(["message" => "update category successfully"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update category error ", title: "incorrect parameter", status: true, detail: "there is no corresponding category for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteCategoryAction::execute((int) $id);
            http_response_code(200);
            return Json::toJson(["message" => "delete category successfully"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete category error ", title: "incorrect parameter", status: true, detail: "there is no corresponding category for this id", statusCode: 400);
        }
    }
}