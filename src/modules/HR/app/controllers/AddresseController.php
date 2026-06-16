<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use Override;
use PostApi\modules\HR\domain\services\addresse\CreateAddresseAction;
use PostApi\modules\HR\domain\services\addresse\DeleteAddresseAction;
use PostApi\modules\HR\domain\services\addresse\GetAddresseCollectionAction;
use PostApi\modules\HR\domain\services\addresse\GetAddresseItemAction;
use PostApi\modules\HR\domain\services\addresse\UpdateAddresseAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class AddresseController implements ApiControllerContract
{
    #[Override]
    public function index()
    {
        $serin = GetAddresseCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetAddresseItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display addresse error ", title: "incorrect paramter", status: true, detail: "there is no corresponding addresse for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['street'], $params['city'],  $params['flat'], $params['country'])) {
            return ViewError::viewProplem("creating addresse error", "missing required paramters error", 1, "missing required paramters street , city  , flat , country", 400);
        }
        try {
            $entity = CreateAddresseAction::execute();
            $serin = GetAddresseItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create addresse error ", title: "incorrect paramter", status: true, detail: "unexpected error creating addresse", statusCode: 400);
        }
    }

    #[Override]
    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['street'], $params['city'],  $params['flat'], $params['country'])) {
            return ViewError::viewProplem("updating addresse error", "missing required paramters error", 1, "missing required paramters street , city  , flat , country", 400);
        }
        try {
            UpdateAddresseAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "update addresse successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update addresse error ", title: "incorrect paramter", status: true, detail: "there is no corresponding addresse for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteAddresseAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "delete addresse successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete addresse error ", title: "incorrect paramter", status: true, detail: "there is no corresponding addresse for this id", statusCode: 400);
        }
    }
}
