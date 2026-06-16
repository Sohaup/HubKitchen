<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use PostApi\modules\HR\domain\services\appraiselResult\CreateAppraiselResultAction;
use PostApi\modules\HR\domain\services\appraiselResult\DeleteAppraiselResultAction;
use PostApi\modules\HR\domain\services\appraiselResult\GetAppraiselResultCollectionAction;
use PostApi\modules\HR\domain\services\appraiselResult\GetAppraiselResultItemAction;
use PostApi\modules\HR\domain\services\appraiselResult\UpdateAppraiselResultAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class AppraiselResultController implements ApiControllerContract
{
    public function index()
    {
        $serin = GetAppraiselResultCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    public function get(string $id)
    {
        try {
            $serin = GetAppraiselResultItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display appraisel result error", title: "incorrect parameter", status: true, detail: "no result for this id", statusCode: 400);
        }
    }

    public function create()
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['template_id'], $body['cycle_id'], $body['critiria_id'], $body['employee_id'], $body['score'], $body['manager_comment'])) {
            return ViewError::viewProplem("creating appraisel result error", "missing required paramters error", 1, "missing required paramters template_id , cycle_id,critiria_id ,employee_id , score , manager_comment", 400);
        }

        try {
            $entity = CreateAppraiselResultAction::execute();
            $serin = GetAppraiselResultItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create appraisel result error", title: "incorrect parameter", status: true, detail: "unexpected error creating result", statusCode: 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $body = $request->body;
        if (!isset($body['template_id'],$body['cycle_id'], $body['critiria_id'], $body['employee_id'], $body['score'], $body['manager_comment'])) {
            return ViewError::viewProplem("creating appraisel result error", "missing required paramters error", 1, "missing required paramters template_id , cycle_id , critiria_id ,employee_id , score , manager_comment", 400);
        }

        try {
            UpdateAppraiselResultAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'update appraisel result successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update appraisel result error", title: "incorrect parameter", status: true, detail: "there is no corresponding result for this id", statusCode: 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteAppraiselResultAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'delete appraisel result successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete appraisel result error", title: "incorrect parameter", status: true, detail: "there is no corresponding result for this id", statusCode: 400);
        }
    }
}
