<?php

namespace PostApi\modules\manegers\app\controllers;

use Error;
use Exception;

use PostApi\modules\manegers\domain\services\maneger\CreateManegerAction;
use PostApi\modules\manegers\domain\services\maneger\UpdateManegerAction;
use PostApi\modules\manegers\domain\services\maneger\DeleteManegerAction;
use PostApi\modules\manegers\domain\services\maneger\GetManegerCollectionAction;
use PostApi\modules\manegers\domain\services\maneger\GetManegerItemAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ManegerController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $manegerRepository = new \PostApi\modules\manegers\app\DB\repositories\ManegerRepository();
            $critiria = [];

            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['user_id'])) {
                $critiria['user_id'] = $body['user_id'];
            }
            if (isset($body['rank'])) {
                $critiria['rank'] = $body['rank'];
            }
            if (isset($body['department_id'])) {
                $critiria['department_id'] = $body['department_id'];
            }

            if (!empty($critiria)) {
                $manegers = $manegerRepository->findBy($critiria);
            } else {
                $manegers = $manegerRepository->findAll();
            }

            $serin = GetManegerCollectionAction::execute($manegers);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {       
        $body = $request->body;
        if (!isset($body['user_id'], $body['rank'], $body['department_id'])) {
            return ViewError::viewProplem('create maneger error', 'missing required paramters', 1, 'missing required paramters user_id , rank , department_id', 400);
        }
        try {
            $createManegerAction = new CreateManegerAction();
            $maneger = $createManegerAction->execute($body); 
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

    public function update(Request $request,string $id)
    {        
        $body = $request->body;
        if (!isset($body['user_id'], $body['rank'], $body['department_id'])) {
            return ViewError::viewProplem('update maneger error', 'missing required paramters', 1, 'missing required paramters user_id , rank , department_id', 400);
        }
        try {
            UpdateManegerAction::execute($id , $body);
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
