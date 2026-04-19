<?php

namespace PostApi\modules\auth\app\controllers;

use Error;
use PostApi\modules\auth\domain\services\tokens\CreateTokenAction;
use PostApi\modules\auth\domain\services\tokens\DeleteTokenAction;
use PostApi\modules\auth\domain\services\tokens\GetTokenItemAction;
use PostApi\modules\auth\domain\services\tokens\GetTokensCollectionAction;
use PostApi\modules\auth\domain\services\tokens\UpdateTokenAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\ViewError;

class TokenController implements ApiControllerContract
{
    public function index()
    {
        $tokensSerin = GetTokensCollectionAction::execute();
        return Json::toJson($tokensSerin);
    }
    public function get(string $id)
    {
        try {           
            $serin = GetTokenItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem("get token error", "unvalid paramter error", 1, "there is no corrosponding token for this id", 400);
        }
    }
    public function create()
    {
        $request = new Request();
        $params = $request->body;
        print_r($params); 
        if (!isset($params['user_id'])) {
            return ViewError::viewProplem("create token error", "missing required paramters error", 1, "missing required paramter user_id", 400);
        }

        try {
            CreateTokenAction::execute($params['user_id']);            
            http_response_code(201);
            return Json::toJson(['message ' => 'create the token successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem("create token error", $error->getMessage(), 1, $error->getMessage(), 500);
        }
    }
    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['is_revoked'])) {
            return ViewError::viewProplem("upadte token error", "missing required paramters error", 1, "missing required paramter is_revoked ", 400);
        }

        try {
            UpdateTokenAction::execute($id, $params['is_revoked']);
            http_response_code(200);
            return Json::toJson(['message' => 'update the token successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem("update token error", $error->getMessage(), 1, $error->getMessage(), 400);
        }
    }
    public function delete(string $id)
    {
        try {
            DeleteTokenAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'delete the token successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem("delete token error", "unvalid paramter error", 1, "there is no corrosponding token for this id", 400);
        }
    }
}
