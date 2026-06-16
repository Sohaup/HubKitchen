<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use PostApi\modules\HR\domain\services\evolutionCritiria\CreateEvolutionCritiriaAction;
use PostApi\modules\HR\domain\services\evolutionCritiria\DeleteEvolutionCritiriaAction;
use PostApi\modules\HR\domain\services\evolutionCritiria\GetEvolutionCritiriaCollectionAction;
use PostApi\modules\HR\domain\services\evolutionCritiria\GetEvolutionCritiriaItemAction;
use PostApi\modules\HR\domain\services\evolutionCritiria\UpdateEvolutionCritiriaAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class EvolutionCritiriaController implements ApiControllerContract
{
    public function index()
    {
        $serin = GetEvolutionCritiriaCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    public function get(string $id)
    {
        try {
            $serin = GetEvolutionCritiriaItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display evolution critiria error", title: "incorrect parameter", status: true, detail: "no result for this id", statusCode: 400);
        }
    }

    public function create()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['template_id'], $params['critiria'], $params['weight'])) {
            return ViewError::viewProplem("creating evouluotion critiria error", "missing required paramters error", 1, "missing required paramters template, critiria, weight ", 400);
        }
        try {
            $entity = CreateEvolutionCritiriaAction::execute();
            $serin = GetEvolutionCritiriaItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create evolution critiria error", title: "incorrect parameter", status: true, detail: "unexpected error creating critiria", statusCode: 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['template_id'], $params['critiria'], $params['weight'])) {
            return ViewError::viewProplem("creating evouluotion critiria error", "missing required paramters error", 1, "missing required paramters template, critiria, weight ", 400);
        }
        try {
            UpdateEvolutionCritiriaAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'update evolution critiria successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update evolution critiria error", title: "incorrect parameter", status: true, detail: "there is no corresponding critiria for this id", statusCode: 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteEvolutionCritiriaAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'delete evolution critiria successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete evolution critiria error", title: "incorrect parameter", status: true, detail: "there is no corresponding critiria for this id", statusCode: 400);
        }
    }
}
