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
    public function index(Request $request)
    {
        try {
            $appraiselResultRepository = new \PostApi\modules\HR\app\DB\repositories\AppraiselResultRepository();
            $body = $request->body;
            $critiria = [];
            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['cycle_id'])) {
                $critiria['cycle_id'] = $body['cycle_id'];
            }
            if (isset($body['employee_id'])) {
                $critiria['employee_id'] = $body['employee_id'];
            }
            if (isset($body['critiria_id'])) {
                $critiria['critiria_id'] = $body['critiria_id'];
            }
            if (isset($body['score'])) {
                $critiria['score'] = $body['score'];
            }
            if (!empty($critiria)) {
                $results = $appraiselResultRepository->findBy($critiria);
                $serin = GetAppraiselResultCollectionAction::execute($results);
            } else {
                $results = $appraiselResultRepository->findAll();
                $serin = GetAppraiselResultCollectionAction::execute($results);
            }
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (\Throwable $err) {
            return ViewError::viewProplem("display appraisel result error", "paramter error", 1, "internal server error", 500);
        }
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

    public function create(Request $request)
    {        
        $body = $request->body;
        if (!isset($body['template_id'], $body['cycle_id'], $body['critiria_id'], $body['employee_id'], $body['score'], $body['manager_comment'])) {
            return ViewError::viewProplem("creating appraisel result error", "missing required paramters error", 1, "missing required paramters template_id , cycle_id,critiria_id ,employee_id , score , manager_comment", 400);
        }

        try {
            $entity = CreateAppraiselResultAction::execute($body);
            $serin = GetAppraiselResultItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create appraisel result error", title: "incorrect parameter", status: true, detail: "unexpected error creating result", statusCode: 400);
        }
    }

    public function update(Request $request,string $id)
    {        
        $body = $request->body;
        if (!isset($body['template_id'],$body['cycle_id'], $body['critiria_id'], $body['employee_id'], $body['score'], $body['manager_comment'])) {
            return ViewError::viewProplem("creating appraisel result error", "missing required paramters error", 1, "missing required paramters template_id , cycle_id , critiria_id ,employee_id , score , manager_comment", 400);
        }

        try {
            UpdateAppraiselResultAction::execute((int)$id , $body);
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
