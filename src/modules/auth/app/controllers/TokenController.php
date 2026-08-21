<?php

namespace PostApi\modules\auth\app\controllers;

use PostApi\modules\auth\domain\services\tokens\CreateTokenAction;
use PostApi\modules\auth\domain\services\tokens\DeleteTokenAction;
use PostApi\modules\auth\domain\services\tokens\GetTokenItemAction;
use PostApi\modules\auth\domain\services\tokens\GetTokensCollectionAction;
use PostApi\modules\auth\domain\services\tokens\UpdateTokenAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;
use Throwable;

class TokenController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $tokensSerin = GetTokensCollectionAction::execute($body);
            return Chache::checkCache($tokensSerin);
        } catch (Throwable $err) {
            return ViewError::viewProplem("get token error", "unvalid paramter error", 1, "internal server error", 500);
        }
        
    }
    public function get(string $id)
    {
        try {
            $serin = GetTokenItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Throwable $error) {
            return ViewError::viewProplem("get token error", "unvalid paramter error", 1, "internal server error", 500);
        }
    }
    public function create(Request $request)
    {
        $params = $request->body;
        print_r($params);
        if (!isset($params['user_id'])) {
            return ViewError::viewProplem("create token error", "missing required paramters error", 1, "missing required paramter user_id", 400);
        }

        try {
            CreateTokenAction::execute($params['user_id']);
            http_response_code(201);
            return Json::toJson(['message ' => 'create the token successfuly']);
        } catch (Throwable $error) {
            return ViewError::viewProplem("create token error", "unvalid paremters error", 1, "internal server error", 500);
        }
    }
    public function update(Request $request, string $id)
    {
        $params = $request->body;
        if (!isset($params['is_revoked'])) {
            return ViewError::viewProplem("upadte token error", "missing required paramters error", 1, "missing required paramter is_revoked ", 400);
        }

        try {
            UpdateTokenAction::execute($id, $params['is_revoked']);
            http_response_code(200);
            return Json::toJson(['message' => 'update the token successfuly']);
        } catch (Throwable $error) {
            return ViewError::viewProplem("update token error","unvalid paremters error", 1, "internal server error", 500);
        }
    }
    public function delete(string $id)
    {
        try {
            DeleteTokenAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'delete the token successfuly']);
        } catch (Throwable $error) {
            return ViewError::viewProplem("delete token error", "unvalid paramter error", 1, "internal server error", 500);
        }
    }
}
