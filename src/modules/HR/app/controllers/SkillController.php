<?php

namespace PostApi\modules\HR\app\controllers;

use Error;

use Override;
use PostApi\modules\HR\domain\services\skills\CreateSkillAction;
use PostApi\modules\HR\domain\services\skills\DeleteSkillAction;
use PostApi\modules\HR\domain\services\skills\GetSkillCollectionAction;
use PostApi\modules\HR\domain\services\skills\GetSkillItemAction;
use PostApi\modules\HR\domain\services\skills\UpdateSkillAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class SkillController implements ApiControllerContract
{
    #[Override]
    public function index()
    {
        $serin = GetSkillCollectionAction::execute();
        return Chache::checkCache($serin);
    }
    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetSkillItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display skill error ", title: "incorrect paramter", status: true, detail: "there is no corresponding skill for this id", statusCode: 400);
        }
    }
    #[Override]
    public function create()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['name'])) {
            return  ViewError::viewProplem("creating skill error", "missing required paramters error", 1, "missing required paramter name ", 400);
        }
        $skill = CreateSkillAction::execute();
        $serin = GetSkillItemAction::execute($skill->getId());
        http_response_code(201);
        return Json::toJson($serin);
    }
    #[Override]
    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['name'])) {
            return  ViewError::viewProplem("updating skill error", "missing required paramters error", 1, "missing required paramter name ", 400);
        }
        try {
            UpdateSkillAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => "update skill successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update skill error ", title: "incorrect paramter", status: true, detail: "there is no corresponding skill for this id", statusCode: 400);
        }
    }
    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteSkillAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => "delete skill successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete skill error ", title: "incorrect paramter", status: true, detail: "there is no corresponding skill for this id", statusCode: 400);
        }
    }
}
