<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\LeadRepository;
use PostApi\modules\sales\domain\entities\Lead;
use PostApi\modules\sales\domain\services\lead\CreateLeadAction;
use PostApi\modules\sales\domain\services\lead\DeleteLeadAction;
use PostApi\modules\sales\domain\services\lead\GetLeadCollectionAction;
use PostApi\modules\sales\domain\services\lead\GetLeadItemAction;
use PostApi\modules\sales\domain\services\lead\UpdateLeadAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class LeadController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        $serin = GetLeadCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetLeadItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display lead error ", title: "incorrect paramter", status: true, detail: "there is no corresponding lead for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {       
        $params = $request->body;
        if (!isset($params['user_id'])) {
            return ViewError::viewProplem("creating lead error", "missing required paramters error", 1, "missing required paramters user_id", 400);
        }
        try {
            $lead = new Lead();           
            $lead->setUserId($params['user_id']);        
            $serin = CreateLeadAction::execute($lead);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create lead error ", title: "incorrect paramter", status: true, detail: "unexpected error creating lead", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request,string $id)
    {        
        $params = $request->body;
        if (!isset($params['user_id'])) {
            return ViewError::viewProplem("updating lead error", "missing required paramters error", 1, "missing required paramters user_id", 400);
        }
        try {
            $leadRepository = new LeadRepository();
            $lead = $leadRepository->findOne($id);
            if (!$lead) {
                return ViewError::viewProplem(type: "update lead error ", title: "incorrect paramter", status: true, detail: "lead not found", statusCode: 400);
            }
            $lead->setUserId($params['user_id']);       
            UpdateLeadAction::execute($lead);
            http_response_code(200);
            return Json::toJson(["message" => "update lead successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update lead error ", title: "incorrect paramter", status: true, detail: "there is no corresponding lead for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteLeadAction::execute($id);
            http_response_code(200);
            return Json::toJson(["message" => "delete lead successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete lead error ", title: "incorrect paramter", status: true, detail: "there is no corresponding lead for this id", statusCode: 400);
        }
    }
}
