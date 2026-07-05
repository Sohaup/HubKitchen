<?php

namespace PostApi\modules\manegers\app\controllers;

use Error;
use Exception;

use PostApi\modules\manegers\domain\services\maneger\CreateManegerAction;
use PostApi\modules\manegers\domain\services\maneger\UpdateManegerAction;
use PostApi\modules\manegers\domain\services\maneger\DeleteManegerAction;
use PostApi\modules\manegers\domain\services\maneger\GetManegerCollectionAction;
use PostApi\modules\manegers\domain\services\maneger\GetManegerItemAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ManegerController
{
    public function index()
    {
        try {
            $serin = GetManegerCollectionAction::execute();
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['user_id'], $body['rank'], $body['department_id'])) {
            return ViewError::viewProplem('create maneger error', 'missing required paramters', 1, 'missing required paramters user_id , rank , department_id', 400);
        }
        try {
            $createManegerAction = new CreateManegerAction();
            $maneger = $createManegerAction->execute(); 
            $serin = GetManegerItemAction::execute($maneger->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetManegerItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no maneger fo this id", 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['user_id'], $body['rank'], $body['department_id'])) {
            return ViewError::viewProplem('update maneger error', 'missing required paramters', 1, 'missing required paramters user_id , rank , department_id', 400);
        }
        try {
            UpdateManegerAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'maneger updated successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no maneger fo this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteManegerAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'maneger deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no maneger fo this id", 400);
        }
    }
}
