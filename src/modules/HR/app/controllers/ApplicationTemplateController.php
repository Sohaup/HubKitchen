<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use Override;
use PostApi\modules\HR\domain\services\applicationTemplate\CreateApplicationTemplateAction;
use PostApi\modules\HR\domain\services\applicationTemplate\DeleteApplicationTemplateAction;
use PostApi\modules\HR\domain\services\applicationTemplate\GetApplicationTemplateCollectionAction;
use PostApi\modules\HR\domain\services\applicationTemplate\GetApplicationTemplateItemAction;
use PostApi\modules\HR\domain\services\applicationTemplate\UpdateApplicationTemplateAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ApplicationTemplateController implements ApiControllerContract
{
    #[Override]
    public function index()
    {
        $serin = GetApplicationTemplateCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetApplicationTemplateItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display application template error ", title: "incorrect paramter", status: true, detail: "there is no corresponding template for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['title'] , $body['description'])) {
            return ViewError::viewProplem("creating departments error", "missing required paramters error", 1, "missing required paramters name", 400);
        }
        try {
            $entity = CreateApplicationTemplateAction::execute();
            $serin = GetApplicationTemplateItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create application template error ", title: "incorrect paramter", status: true, detail: "unexpected error creating template", statusCode: 400);
        }
    }

    #[Override]
    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['title'] , $body['description'])) {
            return ViewError::viewProplem("updating departments error", "missing required paramters error", 1, "missing required paramters name", 400);
        }
        try {
            UpdateApplicationTemplateAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "update application template successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update application template error ", title: "incorrect paramter", status: true, detail: "there is no corresponding template for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteApplicationTemplateAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "delete application template successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete application template error ", title: "incorrect paramter", status: true, detail: "there is no corresponding template for this id", statusCode: 400);
        }
    }
}
