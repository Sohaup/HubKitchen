<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use Override;
use PostApi\modules\HR\domain\services\applications\CreateApplicationAction;
use PostApi\modules\HR\domain\services\applications\DeleteApplicationAction;
use PostApi\modules\HR\domain\services\applications\GetApplicationCollectionAction;
use PostApi\modules\HR\domain\services\applications\GetApplicationItemAction;
use PostApi\modules\HR\domain\services\applications\UpdateApplicationAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class ApplicationController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        try {
            $applicationRepository = new \PostApi\modules\HR\app\DB\repositories\ApplicationRepository();
            $body = $request->body;
            $critiria = [];
            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['name'])) {
                $critiria['name'] = $body['name'];
            }
            if (isset($body['email'])) {
                $critiria['email'] = $body['email'];
            }
            if (isset($body['phone'])) {
                $critiria['phone'] = $body['phone'];
            }
            if (!empty($critiria)) {
                $applications = $applicationRepository->findBy($critiria);
                $serin = GetApplicationCollectionAction::execute($applications);
            } else {
                $applications = $applicationRepository->findAll();
                $serin = GetApplicationCollectionAction::execute($applications);
            }
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (\Throwable $err) {
            return ViewError::viewProplem("display application error", "paramter error", 1, "internal server error", 500);
        }
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetApplicationItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display application error ", title: "incorrect paramter", status: true, detail: "there is no corresponding application for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {
        $params = $request->body;
        if (!isset($params['name'], $params['email'], $params['phone'], $request->files['cv'])) {
            return ViewError::viewProplem("creating application error", "missing required paramters error", 1, "missing required paramters name, email, phone, cv (body or file), ", 400);
        }
        try {
            $application = CreateApplicationAction::execute($params);
            $serin = GetApplicationItemAction::execute((int)$application->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create application error ", title: "incorrect paramter", status: true, detail: "unexpected error creating application", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request, string $id)
    {
        $params = $request->body;
        if (!isset($params['name'], $params['email'], $params['phone'])) {
            return ViewError::viewProplem("updating application error", "missing required paramters error", 1, "missing required paramters name, email, phone", 400);
        }
        try {
            UpdateApplicationAction::execute((int)$id, $params);
            http_response_code(200);
            return Json::toJson(['message' => "update application successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update application error ", title: "incorrect paramter", status: true, detail: "there is no corresponding application for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteApplicationAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => "delete application successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete application error ", title: "incorrect paramter", status: true, detail: "there is no corresponding application for this id", statusCode: 400);
        }
    }
}
